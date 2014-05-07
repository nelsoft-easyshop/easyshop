<?php
    
	require_once('../../vendor/swiftmailer/swift_required.php');
	
	$configDatabase = array(
		'host' => 'localhost:3306',
		'user' => 'root',
		'pass' => '121586',
		'database' => 'easyshop'
	);

	/*
	 *	this_date = today
	 *	past_date = yesterday(day - 1)
	 */
	$configQuery = array(
		'this_date' => date("Y-m-d H:i:s"),
		'past_date' => date("Y-m-d H:i:s",strtotime("-1 days")),
		//'this_date' => date("Y-m-d H:i:s", mktime(0,0,0,9,9,2015)),
		//'past_date' => date("Y-m-d H:i:s", mktime(0,0,0,0,0,0)),
	);

	$configEmail = array(
		'username' => 'noreply@easyshop.ph',
		'password' => '3a5y5h0p_noreply',
		'from_email' => 'noreply@easyshop.ph',
		'from_name' => 'Easyshop.ph',
		'recipients' => array(
			'janz.stephen@gmail.com'
		)
	);
	
	
	/** FETCH DATABASE USER DATA **/
	//$link = mysqli_connect('localhost:3306', 'root', '121586','easyshop');
	$link = mysqli_connect($configDatabase['host'], $configDatabase['user'], $configDatabase['pass'], $configDatabase['database']);

	//CONFIRM CONNECTION
	if (mysqli_connect_errno())
	{
		return "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$rawResult = mysqli_query($link,
					"SELECT username, contactno, email, nickname, fullname, datecreated
					FROM es_member
					WHERE datecreated BETWEEN '" . $configQuery['past_date'] . "' AND '" . $configQuery['this_date'] . "' " .
					"ORDER BY datecreated"
	);
	
	$arrResult = mysqli_fetch_all($rawResult, MYSQLI_ASSOC);
	mysqli_close($link);
	
	/**	Generate CSV Data	**/
	$csvData = 'USERNAME,CONTACT NO,EMAIL,NICKNAME,FULLNAME,DATE CREATED' . PHP_EOL;		
	foreach($arrResult as $userData){
		$csvData .= $userData['username'] . ',' . $userData['contactno'] . ',' . $userData['email'] . ',' . $userData['nickname'] . 
					',' . $userData['fullname'] . ',' . $userData['datecreated'] . PHP_EOL;
	}
	
	/** SEND EMAIL WITH CSV ATTACHED **/
	$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
	  ->setUsername($configEmail['username'])
	  ->setPassword($configEmail['password']);
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance('New members for Easyshop.ph')
	  ->setFrom(array($configEmail['from_email'] => $configEmail['from_name']))
	  ->setTo($configEmail['recipients'])
	  ->setBody('Newly registered members from ' . $configQuery['past_date'] . ' to ' . $configQuery['this_date']);
	
	$filename = 'registered-'.date("M-j-Y").'.csv';
	
	$attachment = Swift_Attachment::newInstance($csvData, $filename, 'application/vnd.ms-excel');  
	$message->attach($attachment);
	
	$numSent = $mailer->send($message);
	
	if(!$numSent){
		return 'Failed to send e-mail to all recipients.';
	}else{
		return 'Successfully sent ' . $numSent . 'emails!';
	}

?>