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
    $configEmail = array(
            'username' => 'noreply@easyshop.ph',
            'password' => '3a5y5h0p_noreply',
            'from_email' => 'noreply@easyshop.ph',
            'from_name' => 'Easyshop.ph',
            'recipients' => array(
                    'stephenjanz@easyshop.ph',
            )
    );

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
                                    "SELECT id_email, email_to, from_member_id, subject, message 
                                    FROM es_email_queue
                                    WHERE status = 'QUEUED' "
    );

    
    mysqli_close($link);
    
    $numSent = 0;
    $failedRecipients = array();
    
    $emailCount = mysqli_num_rows($rawResult);
    
    if( $emailCount > 0 ){
        echo $emailCount . " queued emails fetched! \n\n";
        
        while( $userData = $rawResult->fetch_assoc() ){
        
            if( strlen($userData['email_to']) === 0 ){
                echo "Email id: " . $userData['id_email'] . " has no email address indicated! \n";
                continue;
            }
        
            echo "Sending email - id: " . $userData['id_email'] . " ...\n";
            
            # SEND EMAIL
            $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
              ->setUsername($configEmail['username'])
              ->setPassword($configEmail['password']);
            
            $mailer = Swift_Mailer::newInstance($transport);
            
            $message = Swift_Message::newInstance($userData['subject'])
              ->setFrom(array($configEmail['from_email'] => $configEmail['from_name']))
              ->setTo(array($userData['email_to']));
            
            $image = $message->embed(Swift_Image::fromPath(__DIR__ . '/../../web/assets/images/img_logo.png'));
            $msg = str_replace("img_logo.png", $image, $userData['message']);
            
            $message->setBody($msg, 'text/html');
        
            $result = $mailer->send($message, $failedRecipients);
        
            if($result){
                echo "Email sent! \n";
                $numSent += $result;
                $query = "UPDATE es_email_queue SET `status` = 'SENT', `datemodified` = NOW() WHERE `id_email` = " . $userData['id_email'];
            }
            else{
                echo "Email sending FAILED! \n";
                $query = "UPDATE es_email_queue SET `status` = 'FAILED', `datemodified` = NOW() WHERE `id_email` = " . $userData['id_email'];
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
    
    
    
