<?php

namespace EasyShop\Notifications;

use EasyShop\Entities\EsQueue;
use EasyShop\Entities\EsQueueType;
use EasyShop\Entities\EsQueueStatus as EsQueueStatus;

/**
 *  Email Notification Service using Swift Mailer
 *  Chainable methods except for sendMail.
 *
 *  @author stephenjanz <stephenjanz@easyshop.ph>
 */
class EmailNotification
{
    /**
     *  Email Specific Parameters
     */
    private $recipient;
    private $subject;
    private $msg;
    private $imageArray;
    private $attachment = null;

    /**
     *  Swift Mailer Parameters
     */
    private $emailConfig;
    private $mailer;
    private $message;

    /**
     * Entity manager instance 
     */
    private $em;

    /**
     *  Constructor
     */
    public function __construct($em, $emailConfig)
    {   
        $this->em = $em;
        $this->emailConfig = $emailConfig;

        $transport = \Swift_SmtpTransport::newInstance($this->emailConfig['smtp_host'], $this->emailConfig['smtp_port'], $this->emailConfig['smtp_crypto'])
            ->setUsername($this->emailConfig['smtp_user'])
            ->setPassword($this->emailConfig['smtp_pass']);
        $this->mailer = \Swift_Mailer::newInstance($transport);
        $this->message = \Swift_Message::newInstance();

        return $this;
    }

    /**
     *  Echo details for object instance
     */
    public function __toString()
    {
        print('<pre>' .
            '<br>Subject: ' . $this->subject . PHP_EOL .
            '<br>Message: ' . $this->msg . PHP_EOL .
            '<br>Recipients: '
        );
        print_r($this->recipient);
        print('<br>Email Config: ');
        print_r($this->emailConfig);

        return "";
    }

    /**
     *  Set e-mail recipient
     *
     *  @param array $recipient
     */
    public function setRecipient($recipient=array())
    {
        $this->recipient = is_array($recipient) ? $recipient : array($recipient);

        return $this;
    }

    /**
     *  Set e-mail subject
     *
     *  @param string $subject
     */
    public function setSubject($subject="") 
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     *  Set e-mail message
     *
     *  @param string $msg
     *  @param array $img - array( "/assets/images/image_file.png" )
     *                    - $msg <img> tag should be <img src="image_file.png">
     */
    public function setMessage($msg, $img = array())
    {
        $imgArray = is_array($img) ? $img : array($img);

        $this->imageArray = $imgArray;
        $this->msg = $msg;

        return $this;
    }

    /**
     *  Send e-mail using Swift Mailer
     *
     *  @param array $failedRecipients - pass by reference
     *                                 - returns failed recipients
     *
     *  @return integer $successCount  - number of successful e-mails sent
     */
    public function sendMail( &$failedRecipients = array() )
    {
        $msg = $this->msg;
        $imgArray = $this->imageArray;

        if( count($imgArray)>0 ){
            foreach($imgArray as $imagePath){
                $image = substr($imagePath,strrpos($imagePath,'/')+1,strlen($imagePath));
                if( strpos($msg, $image) !== false ){
                    $embeddedImg = $this->message->embed(\Swift_Image::fromPath(getcwd() . $imagePath));
                    $msg = str_replace($image, $embeddedImg, $msg);
                }
            }
        }

        $this->message
            ->setSubject($this->subject)
            ->setFrom(array($this->emailConfig['from_email'] => $this->emailConfig['from_name']))
            ->setTo($this->recipient)
            ->setBody($msg, 'text/html');

        if($this->attachment !== null){
            $this->message
                 ->attach($this->attachment);
        }

        $successCount = $this->mailer->send($this->message, $failedRecipients);
        
        return $successCount;
    }

    /**
     *  Store email data to queue table.
     *
     *  @return boolean
     */
    public function queueMail()
    {
        if(empty($this->recipient) || empty($this->subject) || empty($this->msg)){
            echo "Incomplete parameters. Please set recipient, subject, and msg.";
            return false;
        }
        else{
            $emailData = [
                'from_email' => $this->emailConfig['from_email'],
                'from_name' => $this->emailConfig['from_name'],
                'recipient' => $this->recipient,
                'subject' => $this->subject,
                'msg' => $this->msg,
                'img' => $this->imageArray
            ];
            $queueType = $this->em->getRepository('EasyShop\Entities\EsQueueType')
                                  ->find($this->emailConfig['queue_type']);
            $queueStatus = $this->em->getRepository('EasyShop\Entities\EsQueueStatus')
                                    ->find(EsQueueStatus::QUEUED);

            $emailQueue = new EsQueue();
            $emailQueue->setData(json_encode($emailData))
                       ->setType($queueType)
                       ->setDateCreated(date_create(date("Y-m-d H:i:s")))
                       ->setStatus($queueStatus);
            $this->em->persist($emailQueue);
            $this->em->flush();
            
            if(strtolower(ENVIRONMENT) === 'development'){
                $this->sendMail();
            }

            return true;
        }
    }

    /**
     * Add attachment into email
     * @param string $attachmentData
     * @param string $fileName
     * @param string $attachmentType
     */
    public function addAttachment($attachmentData, $fileName, $attachmentType)
    {
        $this->attachment = \Swift_Attachment::newInstance(
            $attachmentData,
            $fileName,
            $attachmentType
        );

        return $this;
    }

}

