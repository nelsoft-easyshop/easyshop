<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*********************************************************************
 * overrides to codeigniter session library
 *
 * Handle race condition during session_id updates.
 * - Keep the old session id around in case we have to handle race
 *   conditions.
 * - Changes are marked with the string "old_session_id_changes".
 *
 * session table changes:
 * ALTER TABLE `sessions` ADD COLUMN `old_session_id` VARCHAR(40)  DEFAULT NULL COMMENT 'old session id' AFTER `user_data`,
 *  ADD INDEX `old_session_id`(`old_session_id`);
 * DELETE FROM `sessions`;
 *********************************************************************/
 
/**
 * Fetch the current session data if it exists
 *
 * @return  bool
 */
class MY_Session extends CI_Session
{

	public function sess_read()
	{
	  // Fetch the cookie
	  $session = $this->CI->input->cookie($this->sess_cookie_name);
	 
	  // No cookie?  Goodbye cruel world!...
	  if ($session === NULL)
	  {
		log_message('debug', 'A session cookie was not found.');
		return FALSE;
	  }
	 
	  // Decrypt the cookie data
	  if ($this->sess_encrypt_cookie === TRUE)
	  {
		$session = $this->CI->encrypt->decode($session);
	  }
	  else
	  {
		// encryption was not used, so we need to check the md5 hash
		$hash  = substr($session, strlen($session)-32); // get last 32 chars
		$session = substr($session, 0, strlen($session)-32);
	 
		// Does the md5 hash match?  This is to prevent manipulation of session data in userspace
		if ($hash !==  md5($session.$this->encryption_key))
		{
		  #log_message('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
		  $this->sess_destroy();
		  return FALSE;
		}
	  }
	 
	  // Unserialize the session array
	  $session = $this->_unserialize($session);
	 
	  // Is the session data we unserialized an array with the correct format?
	  if ( ! is_array($session) OR ! isset($session['session_id'], $session['ip_address'], $session['user_agent'], $session['last_activity']))
	  {
		$this->sess_destroy();
		return FALSE;
	  }
	 
	  // Is the session current?
	  if (($session['last_activity'] + $this->sess_expiration) < $this->now)
	  {
		$this->sess_destroy();
		return FALSE;
	  }
	 
	  // Does the IP match?
	  if ($this->sess_match_ip === TRUE && $session['ip_address'] !== $this->CI->input->ip_address())
	  {
		$this->sess_destroy();
		return FALSE;
	  }
	 
	  // Does the User Agent Match?
	  if ($this->sess_match_useragent === TRUE && trim($session['user_agent']) !== trim(substr($this->CI->input->user_agent(), 0, 120)))
	  {
		$this->sess_destroy();
		return FALSE;
	  }
	 
	  // Is there a corresponding session in the DB?
	  if ($this->sess_use_database === TRUE)
	  {
		/*
		 * begin old_session_id_changes
		 *
		 * Search both session_id and old_session_id fields for the
		 * incoming session id.
		 *
		 * used to be:
		 * $this->CI->db->where('session_id', $session['session_id']);
		 *
		 * Manually create the OR condition because it causes the least
		 * disturbance to existing code.
		 *
		 * Store the session id from the cookie so that we can see if we
		 * came in through the old session id later.
		 */
		$this->CI->db->where( '(session_id = ' . $this->CI->db->escape($session['session_id']) . ' OR old_session_id = ' . $this->CI->db->escape($session['session_id']) . ')' );
		$this->cookie_session_id = $session['session_id'];
		/*
		 * end old_session_id_changes
		 */
	 
		if ($this->sess_match_ip === TRUE)
		{
		  $this->CI->db->where('ip_address', $session['ip_address']);
		}
	 
		if ($this->sess_match_useragent === TRUE)
		{
		  $this->CI->db->where('user_agent', $session['user_agent']);
		}
	 
		$query = $this->CI->db->limit(1)->get($this->sess_table_name);
	 
		// No result?  Kill it!
		if ($query->num_rows() === 0)
		{
		  $this->sess_destroy();
		  return FALSE;
		}
	 
		// Is there custom data?  If so, add it to the main session array
		$row = $query->row();
		if ( ! empty($row->user_data))
		{
		  $custom_data = $this->_unserialize($row->user_data);
	 
		  if (is_array($custom_data))
		  {
			foreach ($custom_data as $key => $val)
			{
			  $session[$key] = $val;
			}
		  }
		}
	 
		/*
		 * begin old_session_id_changes
		 *
		 * Pull the session_id from the database to populate the curent
		 * session id because the old one is stale.
		 *
		 * Pull the old_session_id from the database so that we can
		 * compare the current (cookie) session id against it later.
		 */
		$session['session_id'] = $row->session_id;
		$session['old_session_id'] = $row->old_session_id;
		/*
		 * end old_session_id_changes
		 */
	  }
	 
	  // Session is valid!
	  $this->userdata = $session;
	  unset($session);
	 
	  return TRUE;
	}
	 
	// --------------------------------------------------------------------
	 
	/**
	 * Write the session data
	 *
	 * @return  void
	 */
	public function sess_write()
	{
	  // Are we saving custom data to the DB?  If not, all we do is update the cookie
	  if ($this->sess_use_database === FALSE)
	  {
		$this->_set_cookie();
		return;
	  }
	 
	  // set the custom userdata, the session data we will set in a second
	  $custom_userdata = $this->userdata;
	  $cookie_userdata = array();
	 
	  // Before continuing, we need to determine if there is any custom data to deal with.
	  // Let's determine this by removing the default indexes to see if there's anything left in the array
	  // and set the session data while we're at it
	  foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
	  {
		unset($custom_userdata[$val]);
		$cookie_userdata[$val] = $this->userdata[$val];
	  }
	 
	  /*
	   * begin old_session_id_changes
	   *
	   * old_session_id has its own field, but it doesn't need to go into
	   * a cookie because we'll always retrieve it from the database.
	   */
	  unset($custom_userdata['old_session_id']);
	  /*
	   * end old_session_id_changes
	   */
	 
	  // Did we find any custom data?  If not, we turn the empty array into a string
	  // since there's no reason to serialize and store an empty array in the DB
	  if (count($custom_userdata) === 0)
	  {
		$custom_userdata = '';
	  }
	  else
	  {
		// Serialize the custom data array so we can store it
		$custom_userdata = $this->_serialize($custom_userdata);
	  }
	 
	  // Run the update query
	  $this->CI->db->where('session_id', $this->userdata['session_id']);
	  $this->CI->db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata));
	 
	  // Write the cookie.  Notice that we manually pass the cookie data array to the
	  // _set_cookie() function. Normally that function will store $this->userdata, but
	  // in this case that array contains custom data, which we do not want in the cookie.
	  $this->_set_cookie($cookie_userdata);
	}
	 
	// --------------------------------------------------------------------
	 
	/**
	 * Update an existing session
	 *
	 * @return  void
	 */
	public function sess_update()
	{
	  // We only update the session every five minutes by default
	  if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
	  {
		return;
	  }
	 
	  // _set_cookie() will handle this for us if we aren't using database sessions
	  // by pushing all userdata to the cookie.
	  $cookie_data = NULL;
	 
	  /*
	   * begin old_session_id_changes
	   *
	   * Don't need to regenerate the session if we came in by indexing to
	   * the old_session_id), but send out the cookie anyway to make sure
	   * that the client has a copy of the new cookie.
	   *
	   * Do an isset check first in case we're not using the database to
	   * store extra data.  The old_session_id field only exists in the
	   * database.
	   */
	  if ((isset($this->userdata['old_session_id'])) &&
		  ($this->cookie_session_id === $this->userdata['old_session_id']))
	  {
		// set cookie explicitly to only have our session data
		$cookie_data = array();
		foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
		{
		  $cookie_data[$val] = $this->userdata[$val];
		}
	 
		$this->_set_cookie($cookie_data);
		return;
	  }
	  /*
	   * end old_session_id_changes
	   */
	 
	  // Save the old session id so we know which record to
	  // update in the database if we need it
	  $old_sessid = $this->userdata['session_id'];
	  $new_sessid = '';
	  do
	  {
		$new_sessid .= mt_rand(0, mt_getrandmax());
	  }
	  while (strlen($new_sessid) < 32);
	 
	  // To make the session ID even more secure we'll combine it with the user's IP
	  $new_sessid .= $this->CI->input->ip_address();
	 
	  // Turn it into a hash and update the session data array
	  $this->userdata['session_id'] = $new_sessid = md5(uniqid($new_sessid, TRUE));
	  $this->userdata['last_activity'] = $this->now;
	 
	  // Update the session ID and last_activity field in the DB if needed
	  if ($this->sess_use_database === TRUE)
	  {
		// set cookie explicitly to only have our session data
		$cookie_data = array();
		foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
		{
		  $cookie_data[$val] = $this->userdata[$val];
		}
	 
		/*
		 * begin old_session_id_changes
		 *
		 * Save the old session id into the old_session_id field so that
		 * we can reference it later.
		 *
		 * Rewrite the cookie's session id if there are zero affected rows
		 * because that means that another request changed the database
		 * under the current request.  In this case, we want to return a
		 * value consistent with the previous request.  Reread immediately
		 * after the update call here to minimize timing problems.  This
		 * should be in a transaction for databases that support them.
		 *
		 * Also rewrite the userdata so that future calls to sess_write
		 * will output the correct cookie data.
		 *
		 * used to be:
		 * $this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid), array('session_id' => $old_sessid)));
		 */
		$this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid, 'old_session_id' => $old_sessid), array('session_id' => $old_sessid)));
	 
		if ($this->CI->db->affected_rows() === 0)
		{
		  $this->CI->db->where('old_session_id', $this->cookie_session_id);
		  $query = $this->CI->db->get($this->sess_table_name);
	 
		  // We've lost track of the session if there are no results, so
		  // don't set a cookie and just return.
		  if ($query->num_rows() == 0)
		  {
			return;
		  }
	 
		  $row = $query->row();
		  foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
		  {
			$this->userdata[$val] = $row->$val;
			$cookie_data[$val] = $this->userdata[$val];
		  }
	 
		  // Set the request session id to the old session id so that we
		  // won't try to regenerate the cookie again on this request --
		  // just in case sess_update is ever called again (which it
		  // shouldn't be).
		  $this->cookie_session_id = $this->userdata['old_session_id'];
		}
		/*
		 * end old_session_id_changes
		 */
	  }
	 
	  // Write the cookie
	  $this->_set_cookie($cookie_data);
	}

}

 
/*********************************************************************
 * end overrides to codeigniter session library
 *********************************************************************/