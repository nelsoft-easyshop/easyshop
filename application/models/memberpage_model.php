<?php
class memberpage_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("xmlmap");
		$this->load->library("parser");
		$this->config->load("image_path");
	}	
	
	function getLocationLookup()
	{
		$query = $this->xmlmap->getFilenameID('sql/users', 'getLocationLookup');
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
		$data = array();
		
		foreach($row as $r){
			if($r['type'] == 0){
				//CODE FOR MULTIPLE COUNTRY OPTIONS
				//$data['country_lookup'][$r['id_location']] = $r['location'];
				
				$data['country_name'] = $r['location'];
				$data['country_id'] = $r['id_location'];
			}
			else if($r['type'] == 3){
				$data['stateregion_lookup'][$r['id_location']] = $r['location'];
			}
			else if($r['type'] == 4){
				$data['city_lookup'][$r['parent_id']][$r['id_location']] = $r['location'];
			}
		}
		
		$data['json_city'] = json_encode($data['city_lookup'], JSON_FORCE_OBJECT);
		
		return $data;
	}
	
	
	function get_member_by_id($member_id)
	{
		$query = $this->xmlmap->getFilenameID('sql/users', 'get_member');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member', $member_id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
		return $row;
	}
	
	function get_school_by_id($member_id)
	{
		$query = $this->xmlmap->getFilenameID('sql/users', 'get_school');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member', $member_id);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);		
		$data['school']=array();
		$i=0;
		foreach($rows as $row)
			$data['school'][$i++] = $row;
		return $data;
	}
	
	function get_work_by_id($member_id)
	{
		$query = $this->xmlmap->getFilenameID('sql/users', 'get_work');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id_member',$member_id);
		$sth->execute();
		$rows= $sth->fetchAll(PDO::FETCH_ASSOC);
		$data['work'] = array();
		$i=0;
		foreach($rows as $row)
			$data['work'][$i++] = $row;
		return $data;
	}
	
	function getAddress($member_id,$type)
	{
		$query = $this->xmlmap->getFilenameID('sql/users', 'getAddress');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $member_id);
		$sth->bindParam(':type', $type);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		return $row;
	}
	
	function edit_member_by_id($member_id, $data=array())
	{
		if(strlen($data['birthday']) == 0)
			//$data['birthday']='0000-00-00';
			$data['birthday']='0001-01-01';
		$query = $this->xmlmap->getFilenameID('sql/users', 'edit_member');
        $sth = $this->db->conn_id->prepare($query);	
        $sth->bindParam(':fullname', $data['fullname']);
		$sth->bindParam(':nickname', $data['nickname']);
		$sth->bindParam(':gender', $data['gender']);
		$sth->bindParam(':birthday', $data['birthday']);
		$sth->bindParam(':contactno', $data['contactno']);
		$sth->bindParam(':email', $data['email']);
		$sth->bindParam(':id_member', $member_id);     
		$sth->bindParam(':is_contactno_verify', $data['is_contactno_verify']);
		$sth->bindParam(':is_email_verify', $data['is_email_verify']);
		$result = $sth->execute();
		
		return $result;
	}
	
	//function edit_address_by_id($member_id, $data=array())
	function editAddress($member_id, $data=array(), $address_id)
	{	
		if( (string)$address_id === '' ){
			$query = $this->xmlmap->getFilenameID('sql/users', 'insertAddress');
			$sth = $this->db->conn_id->prepare($query);
		}else{
			$query = $this->xmlmap->getFilenameID('sql/users', 'updateAddress');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id_address', $address_id);
		}
        
		$sth->bindParam(':type', $data['addresstype']);
		$sth->bindParam(':id_member', $member_id);
        $sth->bindParam(':stateregion', $data['stateregion']);
		$sth->bindParam(':city', $data['city']);
		$sth->bindParam(':address', $data['address']);
		$sth->bindParam(':country', $data['country']);
		$sth->bindparam(':consignee', $data['consignee']);
		$sth->bindparam(':mobile', $data['mobile']);
		$sth->bindparam(':telephone', $data['telephone']);
		
		$sth->bindParam(':lat', $data['lat']);
		$sth->bindParam(':lng', $data['lng']);
		
		$result = $sth->execute();
	
		return $result;
	}
	
	function edit_school_by_id($member_id, $data=array())
	{
		if(strlen($data['year'])==0)
			$data['year'] = '0000';
		else
		{
			$data['year'] = ($data['year'] < 1901)?1901:$data['year'];
			$data['year']= ($data['year'] > 2155)?2155:$data['year'];
		}
		$query = $this->xmlmap->getFilenameID('sql/users', 'edit_school');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':schoolname', $data['school']);
		$sth->bindParam(':year', $data['year']);
		$sth->bindParam(':level', $data['level']);
		$sth->bindParam(':school_count', $data['school_count']);
		$sth->bindParam(':id_member', $member_id);
		$result = $sth->execute();
		
		return $result;
	}

	function deletePersonalInformation($member_id, $field)
	{
		if($field === 'del_address' ){
			$query = $this->xmlmap->getFilenameID('sql/users', 'delete_address');
		}
		if($field === 'del_school' ){
			$query = $this->xmlmap->getFilenameID('sql/users', 'delete_school');
		}
		if($field === 'del_work' ){
			$query = $this->xmlmap->getFilenameID('sql/users', 'delete_work');
		}
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id', $member_id);
		$result = $sth->execute();
		
		return $result;
	}
	
	function upload_img($uid, $data=array())
	{
		$query = $this->xmlmap->getFilenameID('sql/users', 'get_image');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $uid);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
		$path = $row['imgurl'];	
		
		if(trim($path) === ''){
			$path = $this->config->item('user_img_directory').$path.$row['id_member'].'_'.$row['username'];
		}		
		if(!is_dir($path))
		{
		  mkdir($path,0755,TRUE); 
		}		
		$config['overwrite'] = TRUE;
		$config['file_name'] = 'usersize.png';
		$config['upload_path'] = $path; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '5000';
		$config['max_width']  = '5000';
		$config['max_height']  = '5000';
		$this->upload->initialize($config);  
		
		if ( ! $this->upload->do_upload())
		{
			return array('error' => $this->upload->display_errors());
		}
		else
		{
			$config['image_library'] = 'gd2';
			$config['source_image'] = $path.'/usersize.png';
			$config['maintain_ratio'] = true;
			
			$imageData = $this->upload->data();
			
			// If cropped
			if($data['w'] > 0 && $data['h'] > 0){			
				$config['new_image'] = $path.'/usersize.png';
				$config['width'] = $data['w'];
				$config['height'] = $data['h'];
				$config['x_axis'] = $data['x'];
				$config['y_axis'] = $data['y'];
				$this->image_lib->initialize($config);  
				$this->image_lib->image_process_gd('crop');
				$config['x_axis'] = $config['y_axis'] = '';
			}else if( $imageData['image_width'] > 768 || $imageData['image_height'] > 1024 ){				
				$config['new_image'] = $path.'/usersize.png';
				$config['width'] = 768;
				$config['height'] = 1024;
				$this->image_lib->initialize($config);  
				$this->image_lib->resize();	
			}

			//$this->upload->data(); 
			$config['new_image'] = '150x150.png';
			$config['width'] = 157;
			$config['height'] = 150;
			$this->image_lib->initialize($config);  
			$this->image_lib->resize();		
			
			$config['new_image'] = '60x60.png';
			$config['width'] = 60;
			$config['height'] = 60;
			$this->image_lib->initialize($config);  
			$this->image_lib->resize();

			$query = $this->xmlmap->getFilenameID('sql/users', 'update_imgurl');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':path', $path);
			$sth->bindParam(':id_member', $uid);
			$sth->execute();
			$user_image = img($path.'/150x150.png?'.time());			
			return array('user_image' => $user_image);
		}
	}
	
	function edit_work_by_id($member_id, $data=array())
	{
		$rowcount = count($data) / 4;
		
		if(strlen($data['year'])==0)
			$data['year'] = '0000';
		else
		{
			$data['year'] = ($data['year'] < 1901)?1901:$data['year'];
			$data['year']= ($data['year'] > 2155)?2155:$data['year'];
		}
		
		$query = $this->xmlmap->getFilenameID('sql/users', 'edit_work');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindparam(':id_member', $member_id);
		$sth->bindparam(':companyname', $data['companyname']);
		$sth->bindparam(':designation', $data['designation']);
		$sth->bindparam(':year', $data['year']);
		$sth->bindparam(':count', $data['count']);
		$result = $sth->execute();
		
		return $result;
	}
	
	function get_image($member_id){		
		$path = $this->config->item('user_img_directory');
		$query = $this->xmlmap->getFilenameID('sql/users', 'get_image');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $member_id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
		$img_file = $row['imgurl'].'/150x150.png';
		if(!file_exists($img_file)||(trim($row['imgurl']) === ''))
			$user_image = img(array('src' => $path.'default/150x150.png?'.time(), 'id' => 'user_image'));
		else
			$user_image = img(array('src' => $img_file.'?'.time(), 'id' => 'user_image'));
		return $user_image;
	}	
		
	function select_set($val, $arg=array())
	{
		if($val !== $arg[0])
			return TRUE;
		$this->form_validation->set_message('external_callbacks', 'This field must be set');
		return FALSE;
	}
	
	function is_validdate($date)
	{
		if(trim($date) === '')
			return true;
	
		$comp = preg_split('/[-\/]+/', $date);
		$year = intval($comp[0]);
		$month = intval($comp[1]);
		$day = intval($comp[2]);

		if(checkdate($month, $day, $year)) 
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('external_callbacks', 'The date you entered is invalid');
			return false;
		}
	}
	
	# Check query and adjust filter for order_product_status
	# Current filter : 2 = returned , 6 = dragonpay expired
	# Returns 'active', 'deleted' and 'sold'
	function getUserItemCount($member_id)
	{
		$query = $this->xmlmap->getFilenameID('sql/product','getUserItemCount');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$member_id);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		return $row;
	}
	
	function getUserItemSearchCount($member_id, $schVal,$deleteStatus)
	{
		$query = $this->xmlmap->getFilenameID('sql/product','getUserItemSearchCount');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$member_id, PDO::PARAM_INT);
		$sth->bindParam(':schval', $schVal, PDO::PARAM_STR);
		$sth->bindParam(':delete_status',$deleteStatus, PDO::PARAM_INT);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		$count = (int)$row['product_count'];
		
		return $count;
	}
	
	function getUserItems($member_id, $deleteStatus, $start=0, $nf='%', $of="p.lastmodifieddate" , $osf="DESC" , $itemPerPage=10)
	{
		$query = $this->xmlmap->getFilenameID('sql/product','getUserItems');
		$parseData = array(
			'order_filter' => $of,
			'order_sequence_filter' => $osf
		);
		$query = $this->parser->parse_string($query,$parseData,true);
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$member_id, PDO::PARAM_INT);
		$sth->bindParam(':delete_status',$deleteStatus, PDO::PARAM_INT);
		$sth->bindParam(':start',$start, PDO::PARAM_INT);
		$sth->bindParam(':number',$itemPerPage, PDO::PARAM_INT);
		$sth->bindParam(':name_filter',$nf, PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		$data = array();
		
		foreach($rows as $key=>$row){
			$query = $this->xmlmap->getFilenameID('sql/product','getParent');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$row['cat_id']);
			$sth->execute();
			$parents = $sth->fetchAll(PDO::FETCH_ASSOC);
			$row['parents'] = array();
			foreach($parents as $parent){
				array_push($row['parents'], $parent['name']);
			}
			
			$query = $this->xmlmap->getFilenameID('sql/product','getProductAttributes');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$row['id_product']);
			$sth->execute();
			$attributes = $sth->fetchAll(PDO::FETCH_ASSOC);

			$data_attr = array();
			foreach($attributes as $attribute){
				$index = $attribute['name'];
				if(!array_key_exists($index, $data_attr))
					$data_attr[$index] = array();
				array_push($data_attr[$index],array('value' => $attribute['attr_value'], 'price'=>$attribute['attr_price']));
			}
			$row['data_attr'] = $data_attr;
			
			if(trim($row['product_image_path']) === ''){
				$row['path'] = 'assets/product/default/';
				$row['file'] = 'default_product_img.jpg';
			}
			else{
				$row['product_image_path'] = ($row['product_image_path'][0]=='.')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
				$row['product_image_path'] = ($row['product_image_path'][0]=='/')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
				$rev_url = strrev($row['product_image_path']);
				$row['path'] = substr($row['product_image_path'],0,strlen($rev_url)-strpos($rev_url,'/'));
				$row['file'] = substr($row['product_image_path'],strlen($rev_url)-strpos($rev_url,'/'),strlen($rev_url));
			}
			unset($row['product_image_path']);
			$data[] = $row;
		}
		
		return $data;
	}	
	
	/*
	function getUserItems($member_id, $lastproduct = 0) 	#Retrieves user items to be displayed on dashboard
	{
		if($lastproduct === 0){
			$query = $this->xmlmap->getFilenameID('sql/product','getUserItems');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$member_id);
			$sth->execute();
		}else{
			$query = $this->xmlmap->getFilenameID('sql/product','getMoreUserItems');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$member_id);	
			$sth->bindParam(':last_product',$lastproduct);
			$sth->execute();
		}
		
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		$data = array('active'=>array(),'deleted'=>array(), 'sold_count'=>0);
		
		$data['last_product'] = end($rows)['lastmodifieddate'];
		
		foreach($rows as $key=>$row){
			$query = $this->xmlmap->getFilenameID('sql/product','getParent');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$row['cat_id']);
			$sth->execute();
			$parents = $sth->fetchAll(PDO::FETCH_ASSOC);
			$row['parents'] = array();
			foreach($parents as $parent){
				array_push($row['parents'], $parent['name']);
			}
			
			$query = $this->xmlmap->getFilenameID('sql/product','getProductAttributes');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$row['id_product']);
			$sth->execute();
			$attributes = $sth->fetchAll(PDO::FETCH_ASSOC);

			$data_attr = array();
			foreach($attributes as $attribute){
				$index = $attribute['name'];
				if(!array_key_exists($index, $data_attr))
					$data_attr[$index] = array();
				array_push($data_attr[$index],array('value' => $attribute['attr_value'], 'price'=>$attribute['attr_price']));
			}
			$row['data_attr'] = $data_attr;
			
			if(trim($row['product_image_path']) === ''){
				$row['path'] = 'assets/product/default/';
				$row['file'] = 'default_product_img.jpg';
			}
			else{
				$row['product_image_path'] = ($row['product_image_path'][0]=='.')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
				$row['product_image_path'] = ($row['product_image_path'][0]=='/')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
				$rev_url = strrev($row['product_image_path']);
				$row['path'] = substr($row['product_image_path'],0,strlen($rev_url)-strpos($rev_url,'/'));
				$row['file'] = substr($row['product_image_path'],strlen($rev_url)-strpos($rev_url,'/'),strlen($rev_url));
			}
			unset($row['product_image_path']);
			if($row['is_delete'] === '0')
				array_push($data['active'],$row);
			else if($row['is_delete'] === '1')
				array_push($data['deleted'],$row);
		}
		
		return $data;
	}
	*/
	
	function getVendorDetails($selleruname){
		$query = $this->xmlmap->getFilenameID('sql/users','get_member_by_username');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':username',$selleruname);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		return $row;
	}
	
	/*
	 *	Obtain Transaction Details for Transaction Tab
	 */
	function getTransactionDetails($member_id){
        $this->load->helper('product');
    
		$query = $this->xmlmap->getFilenameID('sql/users','getTransactionDetails');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id', $member_id);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		$data = array();
		$fdata = array('buy' => array(), 'sell' => array(), 'complete' => array('buy'=>array(), 'sell'=>array()));
		
		if(count($row)>0){
			foreach($row as $k=>$temp){
				
				// Ignore transaction 99 via paypal = error || dragonpay expired
				if( ($temp['transac_stat'] == 99 && $temp['payment_method'] == 1) || $temp['transac_stat'] == 2 ){
					continue;
				}
				
				if( !array_key_exists($temp['id_order'], $data) ){
					$data[$temp['id_order']] = array();
				}
				if( !array_key_exists('dateadded', $data[$temp['id_order']]) ){
					$data[$temp['id_order']]['dateadded'] = $temp['dateadded'];
				}
				if( !array_key_exists('transac_stat', $data[$temp['id_order']]) ){
					$data[$temp['id_order']]['transac_stat'] = $temp['transac_stat'];
				}
				if( !array_key_exists('invoice_no', $data[$temp['id_order']]) ){
					$data[$temp['id_order']]['invoice_no'] = $temp['invoice_no'];
				}
				if( !array_key_exists('payment_method', $data[$temp['id_order']]) ){
					$data[$temp['id_order']]['payment_method'] = $temp['payment_method'];
				}
				
				//IF DIRECT BANK DEPOSIT
				if($temp['transac_stat'] == 99 && $temp['payment_method'] == 5){
					if( !isset( $data[$temp['id_order']]['bd_details'] ) ){
						$data[$temp['id_order']]['bd_details'] = array_splice($temp, 39, 7);
						$fdata['bank_name'] = $this->xmlmap->getFilenameID('page/content_files','bank-name');
						$fdata['bank_accname'] = $this->xmlmap->getFilenameID('page/content_files','bank-account-name');
						$fdata['bank_accnum'] = $this->xmlmap->getFilenameID('page/content_files','bank-account-number');
					}
				}
				
				if(!array_key_exists('users', $data[$temp['id_order']]))
					$data[$temp['id_order']]['users'] = array();
				
				if(!array_key_exists('products', $data[$temp['id_order']]))
					$data[$temp['id_order']]['products'] = array();
				
				// Generate product array
				$product = $temp;
				$product = array_slice($product, 8, 14);
				$product['has_shipping_summary'] = 0;
				$product['is_reject'] = $temp['is_reject'];
				//Check if shipping comments exist
				if( trim(strlen($product['courier'])) > 0 && trim(strlen($product['tracking_num'])) > 0 && trim(strlen($product['delivery_date'])) > 0){
					$product['has_shipping_summary'] = 1;
				}
				
				// you are buyer in transaction
				if($member_id == $temp['buyer_id']){
					$userid = $temp['seller_id'];
					$username = $temp['seller'];
				}
				// you are seller in transaction
				else if($member_id == $temp['seller_id']){
					unset($product['seller_id']);
					unset($product['seller']);
					$userid = $temp['buyer_id'];
					$username = $temp['buyer'];
					$data[$temp['id_order']]['buyer_id'] = $temp['buyer_id'];
					$data[$temp['id_order']]['buyer'] = $temp['buyer'];
					$data[$temp['id_order']]['buyer_email'] = $temp['buyer_email'];
				}
				
				if(!array_key_exists($userid, $data[$temp['id_order']]['users']))
					$data[$temp['id_order']]['users'][$userid] = array(
						'name' => $username,
						'feedb_msg' => '',
						'has_feedb' => 0,
						'address' => array_slice($temp, 29, 8)
					);
				
				if(trim($temp['feedb_msg']) !== '' && trim($temp['for_memberid']) !== '' && $userid == $temp['for_memberid']){
					$data[$temp['id_order']]['users'][$userid]['has_feedb'] = 1;
				}
				
				if(!array_key_exists($temp['id_order_product'], $data[$temp['id_order']]['products'])){
					$data[$temp['id_order']]['products'][$temp['id_order_product']] = $product;
					$imagepath[0] = array(
						'product_image_path' => $temp['product_image_path']
					);
					explodeImagePath($imagepath);
					$data[$temp['id_order']]['products'][$temp['id_order_product']]['product_image_path'] = $imagepath[0]['path'] . 'thumbnail/' . $imagepath[0]['file'];
				}
				
				if(!array_key_exists('attr', $data[$temp['id_order']]['products'][$temp['id_order_product']])){
					$data[$temp['id_order']]['products'][$temp['id_order_product']]['attr'] = array();
				}
				
				if($temp['is_other'] === '0'){
					array_push($data[$temp['id_order']]['products'][$temp['id_order_product']]['attr'], array('field' => ucwords(strtolower($temp['attr_name'])), 'value' => ucwords(strtolower($temp['attr_value'])) ));
				}else if($temp['is_other'] === '1'){
					array_push($data[$temp['id_order']]['products'][$temp['id_order_product']]['attr'], array('field' => ucwords(strtolower($temp['field_name'])), 'value' => ucwords(strtolower($temp['value_name'])) ));
				}
			}
			
			// Categorize as buy or sell in final array
			foreach($data as $k=>$temp2){
				// If transaction in progress
				if($temp2['transac_stat'] == 0){
					// Check each product entry if user has responded (status != 0)
					$prodCount = count($temp2['products']);
					$myCount = 0;
					foreach($temp2['products'] as $pk=>$product){
						if($product['status'] != 0){
							$myCount++;
						}
					}
					// If all product entries have response, move to complete array
					if($myCount == $prodCount){
						if(array_key_exists('buyer_id', $temp2) && array_key_exists('buyer', $temp2))
							$fdata['complete']['sell'][$k] = $temp2;
						else
							$fdata['complete']['buy'][$k] = $temp2;
					}else{	//else move to buy or sell
						if(array_key_exists('buyer_id', $temp2) && array_key_exists('buyer', $temp2))
							$fdata['sell'][$k] = $temp2;
						else
							$fdata['buy'][$k] = $temp2;
					}
				}else if($temp2['transac_stat'] == 1){
					if(array_key_exists('buyer_id', $temp2) && array_key_exists('buyer', $temp2))
						$fdata['complete']['sell'][$k] = $temp2;
					else
						$fdata['complete']['buy'][$k] = $temp2;
				}else if($temp2['transac_stat'] == 99 && ( $temp2['payment_method'] == 2 || $temp2['payment_method'] == 5 ) ){ // if pending Dragonpay/Direct BankDeposit transaction
					if(array_key_exists('buyer_id', $temp2) && array_key_exists('buyer', $temp2))
						$fdata['sell'][$k] = $temp2;
					else
						$fdata['buy'][$k] = $temp2;
				}
			}
		}		
		
		return $fdata;
	}
	
	function authenticateUser($data)
	{
		$query = $this->xmlmap->getFilenameID('sql/users', 'authenticateUser');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':username', $data['username']);
		$sth->bindParam(':password', $data['password']);
		$sth->bindParam(':member_id', $data['member_id']);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		return $row['id_member'] ? true : false ;
	}
	
	function addFeedback($temp){
		$query = $this->xmlmap->getFilenameID('sql/users','addFeedback');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $temp['uid']);
		$sth->bindParam(':for_memberid', $temp['for_memberid']);
		$sth->bindParam(':feedb_msg', $temp['feedb_msg']);
		$sth->bindParam(':feedb_kind', $temp['feedb_kind']);
		$sth->bindParam(':order_id', $temp['order_id']);
		$sth->bindParam(':rating1', $temp['rating1']);
		$sth->bindParam(':rating2', $temp['rating2']);
		$sth->bindParam(':rating3', $temp['rating3']);
		$result = $sth->execute();
		
		return $result;
	}
	
	function checkFeedback( $temp ){
		$query = $this->xmlmap->getFilenameID('sql/users','checkFeedback');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $temp['uid']);
		$sth->bindParam(':for_memberid', $temp['for_memberid']);
		$sth->bindParam(':feedb_kind', $temp['feedb_kind']);
		$sth->bindParam(':order_id', $temp['order_id']);
		$result = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $row;
	}
	
	function getFeedback($member_id){
		$query = $this->xmlmap->getFilenameID('sql/users','getFeedback');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$member_id);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		$data = array(
			'youpost_buyer' => array(),
			'youpost_seller' => array(),
			'otherspost_seller' => array(),
			'otherspost_buyer' => array(),
			'afbcount' => 0,
			'rating1' => 0,
			'rating2' => 0,
			'rating3' => 0,
			'rcount' => 0
		);
		
		foreach($row as $k=>$result){
			if($result['member_id'] == $member_id){ //you post feedback
				unset($result['member_id']);
				unset($result['member_name']);
				$temp = array_slice($result,0,7);
				if($result['feedb_kind'] == 0){ //you are buyer
					$data['youpost_buyer'][$result['order_id']][] = $temp;
				}
				else if($result['feedb_kind'] == 1){ //you are seller
					$data['youpost_seller'][$result['order_id']][] = $temp;
				}
			}
			else if($result['for_memberid'] == $member_id){ //others post feedback
				unset($result['for_memberid']);
				unset($result['for_membername']);
				$temp = array_slice($result,0,7);
				$data['rating1'] += $result['rating1'];
				$data['rating2'] += $result['rating2'];
				$data['rating3'] += $result['rating3'];
				$data['rcount']++;
				if($result['feedb_kind'] == 0){ //you are seller
					$data['otherspost_seller'][$result['order_id']][] = $temp;
				}
				else if($result['feedb_kind'] == 1){ //you are buyer
					$data['otherspost_buyer'][$result['order_id']][] = $temp;
				}
			}
			$data['afbcount']++;
		}
		
		if($data['rcount'] !==0 ){
			$data['rating1'] = round($data['rating1'] / $data['rcount']);
			$data['rating2'] = round($data['rating2'] / $data['rcount']);
			$data['rating3'] = round($data['rating3'] / $data['rcount']);
		}

		return $data;
	}
	
	function billing_info($data){	
        $query = $this->xmlmap->getFilenameID('sql/users','getDefaultBillingAccnt');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$data['member_id']);
		$sth->execute();

        $is_default = ($sth->rowCount() == 0)?'1':'0';

        $query = $this->xmlmap->getFilenameID('sql/users','addBillingAccnt');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $data['member_id']);
		$sth->bindParam(':payment_type', $data['payment_type']);
		$sth->bindParam(':user_account', $data['user_account']);
		$sth->bindParam(':bank_id', $data['bank_id']);
		$sth->bindParam(':bank_account_name', $data['bank_account_name']);
		$sth->bindParam(':bank_account_number', $data['bank_account_number']);
        $sth->bindParam(':is_default', $is_default);

		$sth->execute();
		
		$id =  $this->db->conn_id->lastInsertId('id_billing_info');
		return $id;
	}
	
    /*   The update here is implemented by updating the current entry and inserting a copy
     *   of the previous entry for history purposes.
     */
    
	function billing_info_update($data){

        //GET PAYMENT ACCOUNT DETAIL
        $query =  $this->xmlmap->getFilenameID('sql/users','getBillingAccountById');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $data['member_id']);		
		$sth->bindParam(':ibi', $data['ibi']);
		$sth->execute();
        $billing_detail = $sth->fetch(PDO::FETCH_ASSOC);
        //UPDATE CURRENT BILLING DETAIL WITH NEW INFO
        $query = $this->xmlmap->getFilenameID('sql/users','updateBillingAccnt');
		$sth = $this->db->conn_id->prepare($query);	
		$sth->bindParam(':bank_id',  $data['bank_id']);
		$sth->bindParam(':bank_account_name', $data['bank_account_name']);
		$sth->bindParam(':bank_account_number', $data['bank_account_number']);
        $sth->bindParam(':payment_type', $data['payment_type']);
		$sth->bindParam(':user_account', $billing_detail['user_account']);
		$sth->bindParam(':is_default', $billing_detail['is_default']);
        $sth->bindParam(':is_delete', $billing_detail['is_delete']);
        $sth->bindParam(':member_id', $data['member_id']);	
		$sth->bindParam(':ibi', $data['ibi']);
		$sth->execute();
        
        //SAVE A COPY OF THE PREVIOUS ENTRY WITH IS_DELETE = 1
        $query = $this->xmlmap->getFilenameID('sql/users','InsertHistoryBillingAccnt');
		$sth = $this->db->conn_id->prepare($query);
        $billing_detail['is_default'] = 0;
        $billing_detail['is_delete'] = 1;
		$sth->bindParam(':member_id', $data['member_id']);
		$sth->bindParam(':payment_type', $billing_detail['payment_type']);
		$sth->bindParam(':user_account', $billing_detail['user_account']);
		$sth->bindParam(':bank_id', $billing_detail['bank_id']);
		$sth->bindParam(':bank_account_name', $billing_detail['bank_account_name']);
		$sth->bindParam(':bank_account_number', $billing_detail['bank_account_number']);
        $sth->bindParam(':is_default', $billing_detail['is_default']);
        $sth->bindParam(':is_delete', $billing_detail['is_delete'] );
        $sth->bindParam(':date_added', $billing_detail['dateadded']);
        $sth->execute();
	}
	
	function billing_info_default($data){
		
		$query = $this->xmlmap->getFilenameID('sql/users','clearDefaultBillingAccnt');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $data['member_id']);		
		$sth->execute();		

		$query = $this->xmlmap->getFilenameID('sql/users','setDefaultBillingAccnt');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $data['member_id']);		
		$sth->bindParam(':ibi', $data['ibi']);
		$sth->execute();

	}
	
	function billing_info_delete($data){
		// eto e dedelete ko na
		$query = "UPDATE `es_billing_info` SET `is_delete` = 1, `datemodified` = NOW() WHERE `member_id`=:member_id AND `id_billing_info`=:ibi";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $data['member_id']);		
		$sth->bindParam(':ibi', $data['ibi']);
		$sth->execute();
        
		// dito e chcheck ko kung yung na delete ko ay hindi default
		$query = "SELECT ebi.`id_billing_info` FROM `es_billing_info` ebi 
		WHERE ebi.`member_id`=:member_id AND `id_billing_info`=:ibi AND ebi.`is_default` = 1 LIMIT 1 ";		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$data['member_id']);
		$sth->bindParam(':ibi', $data['ibi']);
		$sth->execute();

		if($sth->rowCount() > 0){
			// hanapin ko yung unang data na pwede e default
			$query = "SELECT ebi.`id_billing_info` FROM `es_billing_info` ebi 
			WHERE ebi.`member_id`=:member_id AND ebi.`is_delete` = 0 LIMIT 1 ";		
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':member_id',$data['member_id']);
			$sth->execute();
			$arr = $sth->fetchAll(PDO::FETCH_ASSOC);
	
			if($sth->rowCount() > 0){
				foreach($arr as $value){
					$ibi = $value['id_billing_info'];
				}
				$query = "UPDATE `es_billing_info` SET `is_default` = 1, `datemodified` = NOW() WHERE `member_id`=:member_id AND `id_billing_info`=:ibi";
				$sth = $this->db->conn_id->prepare($query);
				$sth->bindParam(':member_id',$data['member_id']);
				$sth->bindParam(':ibi', $ibi);
				$sth->execute();		
			}		
		}

	}		
	
	function get_billing_info($data){
		$query = "SELECT
			ebi.`id_billing_info`, 
			ebi.`payment_type`,
			ebi.`user_account`,
			ebi.`bank_id`, 
			ebki.`bank_name`,
			ebi.`bank_account_name`,
			ebi.`bank_account_number`,
			ebi.`is_default`,
			ep.`slug`, ep.`name`, ep.`brief`, ep.`createddate`
		FROM `es_billing_info` ebi 
		LEFT JOIN `es_bank_info` ebki ON ebi.`bank_id` = ebki.`id_bank` 
		LEFT JOIN `es_product` ep ON ep.billing_info_id = ebi.`id_billing_info` 
		WHERE ebi.`member_id`=:member_id AND ebi.`is_delete` = 0 ORDER BY ebi.`is_default` DESC, ep.createddate DESC";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$data);
		$sth->execute();
		$rows= $sth->fetchAll(PDO::FETCH_ASSOC);
        $response = array();
        foreach($rows as $row){
            if(!isset($response[$row['id_billing_info']])){
                $response[$row['id_billing_info']]['id_billing_info'] = $row['id_billing_info'];
                $response[$row['id_billing_info']]['payment_type'] = $row['payment_type'];
                $response[$row['id_billing_info']]['user_account'] = $row['user_account'];
                $response[$row['id_billing_info']]['bank_id'] = $row['bank_id'];
                $response[$row['id_billing_info']]['bank_name'] = $row['bank_name'];
                $response[$row['id_billing_info']]['bank_account_name'] = $row['bank_account_name'];
                $response[$row['id_billing_info']]['bank_account_number'] = $row['bank_account_number'];
                $response[$row['id_billing_info']]['is_default'] = $row['is_default'];
                $response[$row['id_billing_info']]['products'] = array();
            }else{
                array_push($response[$row['id_billing_info']]['products'], array('p_slug' => $row['slug'], 'p_name' => $row['name'], 'p_briefdesc' => $row['brief'], 'p_date' => $row['createddate']));
            }
        }
		return $response;	
	}		
    

    function getAllBanks(){
		$query = "SELECT * FROM es_bank_info";
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$rows= $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rows;	
	}

    function get_bank($bank, $toggle){
		if($toggle == 'name'){
            $query = "SELECT `id_bank` AS 'id', `bank_name` AS 'name' FROM `es_bank_info` WHERE `bank_name` LIKE CONCAT(CONCAT('%',:bank),'%')";
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':bank', $bank, PDO::PARAM_STR);	
        }else{
			$query = "SELECT `id_bank` AS 'id', `bank_name` AS 'name' FROM `es_bank_info`";
            $sth = $this->db->conn_id->prepare($query);
        }
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $row;							
	}
	
    function isBankAccountUnique($data){
		$query = "SELECT * FROM `es_billing_info` WHERE `member_id` = :member_id AND `bank_account_number` = :bank_account_number AND `is_delete` = 0";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$data['member_id']);
		$sth->bindParam(':bank_account_number',$data['bank_account_number']);	
		$sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $data['ibi'] = isset($data['ibi'])?$data['ibi']:0;
        $response = false;
        if((!$row)||(intval($row['id_billing_info'],10) == intval($data['ibi'],10))){
            $response = true;
        } 
        return $response;
	}	
    
    function getProductsByBillingInfo($billing_id){
        $query = "SELECT id_product, name, brief FROM `es_product` WHERE `billing_info_id` = :billing_id AND `is_delete` = 0 AND `is_draft` = 0";
        $sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':billing_id',$billing_id);
		$sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
	function getNextPayout($member_id)
	{
		$dateToday = getdate();
		$month = $dateToday['mon'];
		$day = $dateToday['mday'];
		$year = $dateToday['year'];
		
		//lastmonth 16 to 30/31
		if( $day <= 5 ){ 
			$startDate = date( "Y-m-d H:i:s", mktime(0,0,0,$month-1,16,$year) );
			$endDate = date( "Y-m-d H:i:s", mktime(23,59,59,$month-1,date('d', strtotime('last day of previous month')),$year) );
			$payoutDate = date("Y-m-d", mktime(0,0,0,$month,5,$year));
		}else if( $day > 20 ){
			$startDate = date( "Y-m-d H:i:s", mktime(0,0,0,$month,16,$year) );
			$endDate = date( "Y-m-d H:i:s", mktime(23,59,59,$month,date('t'),$year) );
			$payoutDate = date("Y-m-d", mktime(0,0,0,$month+1,5,$year));
		// thismonth 1st to 15th
		}else if( $day > 5 && $day <= 20 ){
			$startDate = date( "Y-m-d H:i:s", mktime(0,0,0,$month,1,$year) );
			$endDate = date( "Y-m-d H:i:s", mktime(23,59,59,$month,15,$year) );
			$payoutDate = date("Y-m-d", mktime(0,0,0,$month,20,$year));
		}
		
		$query = $this->xmlmap->getFilenameID('sql/users','getNextPayout');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$member_id);
		$sth->bindParam(':start_date',$startDate);
		$sth->bindParam(':end_date',$endDate);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		$data = array(
			'list' => array(),
			'payout' => 0,
			'start_date' => $startDate,
			'end_date' => $endDate,
			'payout_date' => $payoutDate
		);
		
		foreach($row as $r){
			if( !isset($data['list'][$r['order_id']]) ){
				$data['list'][$r['order_id']]['invoice'] = $r['invoice_no'];
				$data['list'][$r['order_id']]['tx_net'] = 0;
				$data['list'][$r['order_id']]['product'] = array();
			}
			
			$data['list'][$r['order_id']]['product'][] = array(
				'name' => $r['name'],
				'qty' => $r['order_quantity'],
				'base_price' => $r['price'],
				'handling_fee' => $r['handling_fee'],
				'prd_total_price' => $r['total'],
				'payment_method_charge' => $r['payment_method_charge'],
				'easyshop_charge' => $r['easyshop_charge'],
				'prd_net' => $r['net']
			);
			
			$data['payout'] += $r['net'];
			$data['list'][$r['order_id']]['tx_net'] += $r['net'];
		}
		
		return $data;
	}
	
	function getUserBalance($member_id)
	{
		$query = $this->xmlmap->getFilenameID('sql/users','getUserBalance');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id',$member_id);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		$data = array(
			'list' => array(),
			'balance' => 0
		);
		
		foreach($row as $r){
			if( !isset($data['list'][$r['order_id']]) ){
				$data['list'][$r['order_id']]['invoice'] = $r['invoice_no'];
				$data['list'][$r['order_id']]['tx_net'] = 0;
				$data['list'][$r['order_id']]['product'] = array();
			}
			
			$data['list'][$r['order_id']]['product'][] = array(
				'name' => $r['name'],
				'qty' => $r['order_quantity'],
				'base_price' => $r['price'],
				'handling_fee' => $r['handling_fee'],
				'prd_total_price' => $r['total'],
				'payment_method_charge' => $r['payment_method_charge'],
				'easyshop_charge' => $r['easyshop_charge'],
				'prd_net' => $r['net']
			);
			
			$data['balance'] += $r['net'];
			$data['list'][$r['order_id']]['tx_net'] += $r['net'];
		}
		
		return $data;
	}
	
}

/* End of file memberpage_model.php */
/* Location: ./application/models/memberpage_model.php */