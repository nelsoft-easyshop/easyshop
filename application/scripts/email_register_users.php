<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    echo "Loading swiftmailer...\n";
    require_once(__DIR__ . '/../../vendor/swiftmailer/swiftmailer/lib/swift_required.php');
    
    /**
     * Database params
     */

    $configDatabase = require dirname(__FILE__). '/../config/param/database.php';


    /**
     * Query params
     * 
     *	this_date = today
     *	past_date = yesterday(day - 1)
     */
    $configQuery = array(
            'this_date' => date("Y-m-d H:i:s"),
            'past_date' => date("Y-m-d H:i:s",strtotime("-7 days"))
    );

    /**
     * Email params
     */
    $configEmail = array(
            'username' => 'noreply@easyshop.ph',
            'password' => '3a5y5h0p_noreply',
            'from_email' => 'noreply@easyshop.ph',
            'from_name' => 'Easyshop.ph',
            'recipients' => [
                    'samgavinio@easyshop.ph',
                    'trixia.chua@easyshop.ph',
            ]
    );

    echo "Fetching newly registered users between " . $configQuery['past_date'] . " and " . $configQuery['this_date'] . " ...\n"; 

    $dbh = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'], $configDatabase['user'], $configDatabase['password']);

    $sql = "SELECT username, contactno, email, nickname, fullname, datecreated
            FROM es_member
            WHERE datecreated BETWEEN :past_date AND :this_date 
            
            UNION
            
            SELECT '','',email,'','',datecreated
            FROM es_subscribe
            WHERE datecreated BETWEEN :past_date AND :this_date 
            
            ORDER BY datecreated";

    echo "Generating CSV Data... \n";

    $prepareStatement = $dbh->prepare($sql);
    $prepareStatement->bindParam("past_date", $configQuery['past_date'], PDO::PARAM_STR);
    $prepareStatement->bindParam("this_date", $configQuery['this_date'], PDO::PARAM_STR);
    $prepareStatement->execute();
    $rawUserData = $prepareStatement->fetchAll(PDO::FETCH_ASSOC);

    /**	Generate CSV Data	**/
    $csvData = 'USERNAME,CONTACT NO,EMAIL,NICKNAME,FULLNAME,DATE CREATED' . PHP_EOL;

    foreach( $rawUserData as $userData){

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
                            ->setFrom([$configEmail['from_email'] => $configEmail['from_name']])
                            ->setTo($configEmail['recipients'])
                            ->setBody('Newly registered members from ' . $configQuery['past_date'] . ' to ' . $configQuery['this_date']);

    $filename = 'registered-'.date("M-j-Y").'.csv';
    $attachment = Swift_Attachment::newInstance($csvData, $filename, 'application/vnd.ms-excel');  
    $message->attach($attachment);

    $numSent = $mailer->send($message);

    if(!$numSent){
            echo "ERROR : Failed to send all emails!\n";
    }
    else{
            echo "Successfully sent " . $numSent . "emails!\n";
    }
    
    
