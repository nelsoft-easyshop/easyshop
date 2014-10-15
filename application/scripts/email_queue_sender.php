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

    #FETCH DATABASE USER DATA
    $link = mysqli_connect($configDatabase['host'], $configDatabase['user'], $configDatabase['password'], $configDatabase['dbname']);

    #CONFIRM CONNECTION
    if (mysqli_connect_errno()){
            echo "ERROR : Failed to connect to MySQL: " . mysqli_connect_error();
    }
    else{
            echo "Successfully connected to database! \n";
    }

    $rawResult = mysqli_query($link,
                                    "SELECT id_queue, data, type, date_created, date_executed, status
                                    FROM es_queue
                                    WHERE type = 1 
                                        AND status = 1"
    );

    
    mysqli_close($link);
    
    $numSent = 0;
    $failedRecipients = array();
    
    $emailCount = mysqli_num_rows($rawResult);
    
    if( $emailCount > 0 ){
        echo $emailCount . " queued emails fetched! \n\n";
        
        while( $userData = $rawResult->fetch_assoc() ){
        
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
            
            foreach($emailData)

            //$image = $message->embed(Swift_Image::fromPath(__DIR__ . '/../../web/assets/images/img_logo.png'));
            //$msg = str_replace("img_logo.png", $image, $userData['message']);
            
            $message->setBody($emailData['msg'], 'text/html');
        
            $result = $mailer->send($message, $failedRecipients);
        
            if($result){
                echo "Email sent! \n";
                $numSent += $result;
                $query = "UPDATE es_queue SET `status` = '2', `date_executed` = NOW() WHERE `id_queue` = " . $userData['id_queue'];
            }
            else{
                echo "Email sending FAILED! \n";
                $query = "UPDATE es_queue SET `status` = '99', `date_executed` = NOW() WHERE `id_queue` = " . $userData['id_queue'];
            }
        
            $link = mysqli_connect($configDatabase['host'], $configDatabase['user'], $configDatabase['password'], $configDatabase['dbname']);
            if (mysqli_connect_errno()){
                    echo "ERROR : Failed to connect to update database! : " . mysqli_connect_error() . "\n\n";
            }
            else{
                    echo "Successfully updated database! \n\n";
            }
            $r = mysqli_query($link, $query);
            mysqli_close($link);
        }

        echo "\nSuccessfully sent " . $numSent . " emails!\n";
        
        if( count($failedRecipients) > 0 ){
            echo "\n\nFailed to send emails to the following users:\n";
            foreach( $failedRecipients as $fr ){
                echo "    " . $fr . "\n";
            }
        }
    
    }
    else{
        echo "No queued emails at the moment. \n\n";
    }
    
    
    
