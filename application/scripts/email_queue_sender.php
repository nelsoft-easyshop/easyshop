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
            WHERE type = :queue 
                AND status = :status";

    $queueDbh = $dbh->prepare($sql);
    $queueDbh->bindParam("queue", $configEmail['queue_type'], PDO::PARAM_INT);
    $queueDbh->bindParam("status", $configEmail['status']['queued'], PDO::PARAM_INT);
    $queueDbh->execute();
    $rawData = $queueDbh->fetchAll(PDO::FETCH_ASSOC);

    $numSent = 0;
    $emailCounter = 0;
    $emailCount = count($rawData);
    $failedRecipients = array();

    echo "Fetched " . $emailCount . " queued emails!\n\n";

    foreach($rawData as $userData){

        $emailCounter++;

        $emailData = json_decode($userData['data'], true);

        if( count($emailData['recipient']) === 0 ){
            echo "Queue id: " . $userData['id_queue'] . " has no email address indicated! \n";
            continue;
        }
    
        echo "Sending email - queue id: " . $userData['id_queue'] . " ...\n";
        try {
            # SEND EMAIL
            $transport = Swift_SmtpTransport::newInstance($configEmail['smtp_host'], $configEmail['smtp_port'], $configEmail['smtp_crypto'])
                                            ->setUsername($configEmail['smtp_user'])
                                            ->setPassword($configEmail['smtp_pass']);

            $mailer = Swift_Mailer::newInstance($transport);
            
            $message = Swift_Message::newInstance($emailData['subject'])
                                    ->setFrom([$configEmail['from_email'] => $configEmail['from_name']])
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
                $status = $configEmail['status']['sent'];
            }
            else{
                echo "Email sending FAILED! \n";
                $status = $configEmail['status']['failed'];
            }
        } 
        catch (Exception $e) {
            echo "Email sending FAILED! \n";
            $status = $configEmail['status']['failed'];
        }
        $exec_date = date("Y-m-d H:i:s");

        $updateSql = "UPDATE es_queue SET `status` = :status, `date_executed` = :exec_date WHERE `id_queue` = :queue_id";
        $dbhUpdate = new PDO("mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'], $configDatabase['user'], $configDatabase['password']);
        $queueUpdate = $dbhUpdate->prepare($updateSql);
        $queueUpdate->bindParam("status", $status, PDO::PARAM_INT);
        $queueUpdate->bindParam("exec_date", $exec_date);
        $queueUpdate->bindParam("queue_id", $userData['id_queue'], PDO::PARAM_INT);
        $queueUpdate->execute();

    }

    echo "\nFetched " . $emailCounter . " emails!\n";
    echo "\nSuccessfully sent " . $numSent . " emails!\n";
        
    if( count($failedRecipients) > 0 ){
        echo "\n\nFailed to send emails to the following users:\n";
        foreach( $failedRecipients as $fr ){
            echo "    " . $fr . "\n";
        }
    }
    
    
    
    
