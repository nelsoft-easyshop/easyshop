<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    echo "Loading swiftmailer...\n";
    require_once(__DIR__ . '/../../vendor/swiftmailer/swiftmailer/lib/swift_required.php');
    
    /*
     * Database params
     */
    $configDatabase = require dirname(__FILE__). '/../config/param/database.php';

    /*
     * Email params
     */
    $configEmail = require(__DIR__ . "/../config/email_swiftmailer.php");


    echo "\nFetching queued emails...\n"; 

    $dbh = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'], $configDatabase['user'], $configDatabase['password']);

    $sql = "SELECT id_queue, data, type, date_created, date_executed, status
            FROM es_queue
            WHERE type = " . $configEmail['queue_type'] . " 
                AND status = " . $configEmail['status']['queued'];

    $numSent = 0;
    $emailCounter = 0;
    $failedRecipients = array();

    foreach($dbh->query($sql) as $userData){

        $emailCounter++;

        $emailData = json_decode($userData['data'], TRUE);

        if( count($emailData['recipient']) === 0 ){
            echo "Queue id: " . $userData['id_queue'] . " has no email address indicated! \n";
            continue;
        }
    
        echo "Sending email - queue id: " . $userData['id_queue'] . " ...\n";
        
        # SEND EMAIL
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
          ->setUsername($configEmail['smtp_user'])
          ->setPassword($configEmail['smtp_pass']);
        
        $mailer = Swift_Mailer::newInstance($transport);
        
        $message = Swift_Message::newInstance($emailData['subject'])
          ->setFrom(array($configEmail['from_email'] => $configEmail['from_name']))
          ->setTo($emailData['recipient']);
        
        // Embed Image
        foreach($emailData["img"] as $imagePath){
            $image = substr($imagePath,strrpos($imagePath,'/')+1,strlen($imagePath));
            if( strpos($emailData['msg'], $image) !== false ){
                $embeddedImg = $message->embed(\Swift_Image::fromPath(__DIR__ . "/../../web/" . $imagePath));
                $emailData['msg'] = str_replace($image, $embeddedImg, $emailData['msg']);
            }
        }

        $message->setBody($emailData['msg'], 'text/html');
    
        $result = $mailer->send($message, $failedRecipients);
    
        if($result){
            echo "Email sent! \n";
            $numSent += $result;
            $query = "UPDATE es_queue SET `status` = " . $configEmail['status']['sent'] . ", `date_executed` = NOW() WHERE `id_queue` = " . $userData['id_queue'];
        }
        else{
            echo "Email sending FAILED! \n";
            $query = "UPDATE es_queue SET `status` = " . $configEmail['status']['failed'] . ", `date_executed` = NOW() WHERE `id_queue` = " . $userData['id_queue'];
        }

        $dbhUpdate = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'], $configDatabase['user'], $configDatabase['password']);
        $dbhUpdate->query($query);

    }

    echo "\nFetched " . $emailCounter . " emails!\n";
    echo "\nSuccessfully sent " . $numSent . " emails!\n";
        
    if( count($failedRecipients) > 0 ){
        echo "\n\nFailed to send emails to the following users:\n";
        foreach( $failedRecipients as $fr ){
            echo "    " . $fr . "\n";
        }
    }
    
    
    
    
