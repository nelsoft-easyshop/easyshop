<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    echo "Loading swiftmailer...\n";
    require_once(__DIR__ . '/../../vendor/swiftmailer/swiftmailer/lib/swift_required.php');
    
    /**
     * Database params
     */
    $configDatabase = array(
            'host' => 'ip-172-31-3-69.ap-southeast-1.compute.internal',
            'user' => 'easyshop',
            'pass' => 'SECRETmy5ql',
            'database' => 'easyshop'
    );

    /**
     * Query params
     * 
     *	this_date = today
     *	past_date = yesterday(day - 1)
     */
    $configQuery = array(
            'this_date' => date("Y-m-d H:i:s"),
            'past_date' => date("Y-m-d H:i:s",strtotime("-7 days"))
            //'this_date' => date("Y-m-d H:i:s", mktime(0,0,0,9,9,2015)),
            //'past_date' => date("Y-m-d H:i:s", mktime(0,0,0,0,0,0)),
    );

    /**
     * Email params
     */
    $configEmail = array(
            'username' => 'noreply@easyshop.ph',
            'password' => '3a5y5h0p_noreply',
            'from_email' => 'noreply@easyshop.ph',
            'from_name' => 'Easyshop.ph',
            'recipients' => array(
                    'samgavinio@easyshop.ph',
                    'marie.damocles@easyshop.ph'
            )
    );

    echo "Initializing database query...\n"; 

    /** FETCH DATABASE USER DATA **/
    $link = mysqli_connect($configDatabase['host'], $configDatabase['user'], $configDatabase['pass'], $configDatabase['database']);

    //CONFIRM CONNECTION
    if (mysqli_connect_errno())
    {
            echo "ERROR : Failed to connect to MySQL: " . mysqli_connect_error();
    }else{
            echo "Successfully connected to database! \n";
    }

    $rawResult = mysqli_query($link,
                                    "SELECT username, contactno, email, nickname, fullname, datecreated
                                    FROM es_member
                                    WHERE datecreated BETWEEN '" . $configQuery['past_date'] . "' AND '" . $configQuery['this_date'] . "' 
                                    
                                    UNION
                                    
                                    SELECT '','',email,'','',datecreated
                                    FROM es_subscribe
                                    WHERE datecreated BETWEEN '" . $configQuery['past_date'] . "' AND '" . $configQuery['this_date'] . "' 
                                    
                                    ORDER BY datecreated"
    );

    $arrResult = mysqli_fetch_all($rawResult, MYSQLI_ASSOC);
    mysqli_close($link);

    echo "SQL connection closed. Data fetched. \n";
    echo "Preparing CSV data... \n";

    /**	Generate CSV Data	**/
    $csvData = 'USERNAME,CONTACT NO,EMAIL,NICKNAME,FULLNAME,DATE CREATED' . PHP_EOL;		
    foreach($arrResult as $userData){
            $csvData .= $userData['username'] . ',' . $userData['contactno'] . ',' . $userData['email'] . ',' . $userData['nickname'] . 
                                    ',' . $userData['fullname'] . ',' . $userData['datecreated'] . PHP_EOL;
    }

    echo "Preparing email... \n";

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
            echo "ERROR : Failed to send all emails!\n";
    }else{
            echo "Successfully sent " . $numSent . "emails!\n";
    }
