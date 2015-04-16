<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class EmailQueueSender extends ScriptBaseClass
{
    private $connection;
    private $emailConfig;
    private $emailService;

    /**
     * Constructor
     * @param string                                   $hostName
     * @param string                                   $dbUsername
     * @param string                                   $dbPassword
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     */
    public function __construct(
        $hostName,
        $dbUsername,
        $dbPassword,
        $emailService,
        $configLoader,
        $viewParser
    ) {
        parent::__construct($emailService, $configLoader, $viewParser);

        $this->connection = new PDO(
            $hostName,
            $dbUsername,
            $dbPassword
        );

        $this->emailConfig = $configLoader->getItem('email_swiftmailer');
        $this->emailService = $emailService;
    }

    public function execute()
    {
        $queuedMails = $this->getQueuedMail(
            $this->emailConfig['queue_type'], 
            $this->emailConfig['status']['queued']
        );

        echo "\nScanning of data started (".date('M-d-Y h:i:s A').") \n \n";
        $numSent = 0;
        foreach ($queuedMails as $mail) {
            $mailData = json_decode($mail['data'], true);
            if(isset($mailData['recipient']) === false 
                || count($mailData['recipient']) === 0 ){
                echo "Queue id: " . $mail['id_queue'] . " has no email address indicated!\n";
                continue;
            }
            $queueId = $mail['id_queue'];
            echo "Sending email - Queue ID: " . $queueId . " ... ";
            $emailResult = $this->constructSendMail($mailData);
            if($emailResult){
                echo "\033[0;32m[SENT]\033[0m\n";
                $numSent += $emailResult;
                $status = $this->emailConfig['status']['sent'];
            }
            else{
                echo "\033[0;31m[FAILED]\033[0m\n";
                $status = $this->emailConfig['status']['failed'];
            }
            $this->updateMailStatus($queueId, $status);
        }
        echo "\nFetched " . count($queuedMails) . " emails!\n";
        echo "\nSuccessfully sent " . $numSent . " emails!\n";
        echo "\nScanning of data ended (".date('M-d-Y h:i:s A').") \n \n";
    }

    /**
     * Get all queued email in database
     * @param  integer $type
     * @param  integer $status
     * @return array
     */
    private function getQueuedMail($type, $status)
    {
        $selectQueuedMailQuery = "
        SELECT 
            id_queue, data, type, date_created, date_executed, status
        FROM
            es_queue
        WHERE
            type = :type AND status = :status
        ";

        $selectQueuedMail = $this->connection->prepare($selectQueuedMailQuery);
        $selectQueuedMail->bindValue(":type", $type);
        $selectQueuedMail->bindValue(":status", $status);
        $selectQueuedMail->execute();
        $queuedMail = $selectQueuedMail->fetchAll(PDO::FETCH_ASSOC);

        return count($queuedMail) > 0 ? $queuedMail : [];
    }

    /**
     * construct message and send emal
     * @param  mixed $emailData
     * @return integer
     */
    private function constructSendMail($emailData)
    {
        $emailResult = $this->emailService
                            ->setRecipient($emailData['recipient'])
                            ->setSubject($emailData['subject'])
                            ->setMessage($emailData['msg'], $emailData['img'])
                            ->sendMail();

        return $emailResult;
    }

    /**
     * function update queued mail status
     * @param  integer $queueId
     * @param  integer $status
     */
    private function updateMailStatus($queueId, $status)
    {
        $executeDate = date("Y-m-d H:i:s");

        $updateQuery = "
            UPDATE es_queue 
            SET 
                `status` = :status,
                `date_executed` = :exec_date 
            WHERE 
                `id_queue` = :queue_id";
        $queueUpdate = $this->connection->prepare($updateQuery);
        $queueUpdate->bindValue("status", $status, PDO::PARAM_INT);
        $queueUpdate->bindValue("exec_date", $executeDate);
        $queueUpdate->bindValue("queue_id", $queueId, PDO::PARAM_INT);
        $queueUpdate->execute();
    }
}

$emailQueueSender = new EmailQueueSender(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $emailService,
    $configLoader,
    $viewParser
);

$emailQueueSender->execute();
