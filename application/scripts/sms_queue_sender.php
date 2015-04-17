<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();
$smsService = $CI->kernel->serviceContainer['mobile_notification'];

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;
use EasyShop\Entities\EsQueueStatus as EsQueueStatus;
use EasyShop\Entities\EsQueueType as EsQueueType;

class SmsQueueSender extends ScriptBaseClass
{
    private $connection;
    private $smsConfig;
    private $smsService;

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
        $viewParser,
        $smsService
    ) {
        parent::__construct($emailService, $configLoader, $viewParser);

        $this->connection = new PDO(
            $hostName,
            $dbUsername,
            $dbPassword
        );

        $this->smsConfig = $configLoader->getItem('sms');
        $this->smsService = $smsService;
    }

    /**
     * execute script
     */
    public function execute()
    {
        $queuedSMS = $this->getQueuedSMS();
        $numSuccess = 0;
        $numFailed = 0;
        echo "\nFetched " . count($queuedSMS) . " queued sms!\n\n";
        foreach ($queuedSMS as $sms) {
             echo "Sending SMS - Queue id: " . $sms['id_queue'] . "...";

            $smsData = json_decode($sms['data'], true);
            $sendSMS = $this->smsService->setMobile($smsData['number'])
                                        ->setMessage($smsData['message'])
                                        ->sendSMS();
            if ($sendSMS) {
                $decodeSms = json_decode($sendSMS, true);
                if (strtolower($decodeSms['status']) === "success") {
                    $numSuccess++;
                    echo "\t\033[0;32m[SENT]\033[0m\n";
                    $smsStatus = EsQueueStatus::SENT;
                }
                else {
                    $numFailed++;
                    echo "\t\033[0;31m[FAILED]\033[0m\n";
                    $smsStatus = EsQueueStatus::FAILED;
                }
            }
            else {
                $numFailed++;
                echo "\t\033[0;31m[FAILED]\033[0m\n";
                $smsStatus = EsQueueStatus::FAILED;
            }

            $this->updateSMSStatus($sms['id_queue'], $smsStatus);
        }

        echo "\n\033[0;32mSending Done.\033[0m\n";
        echo "\nSuccess: " . $numSuccess . "\n";
        echo "Failed: " . $numFailed . "\n";
        echo "Total: " . count($queuedSMS) . " SMS\n\n";
    }

    /**
     * Get all queued sms
     * @return array
     */
    private function getQueuedSMS()
    {
        $selectQueuedSmsQuery = "
        SELECT id_queue, data, type, date_created, date_executed, status
        FROM es_queue
        WHERE type = :queue 
            AND status = :status";

        $selectQueuedSMS = $this->connection->prepare($selectQueuedSmsQuery);
        $selectQueuedSMS->bindValue("queue", EsQueueType::TYPE_MOBILE, PDO::PARAM_INT);
        $selectQueuedSMS->bindValue("status", EsQueueStatus::QUEUED, PDO::PARAM_INT);
        $selectQueuedSMS->execute();
        $queuedSMS = $selectQueuedSMS->fetchAll(PDO::FETCH_ASSOC);

        return $queuedSMS;
    }

    /**
     * Update sms queue status
     * @param  integer $queueId
     * @param  integer $status
     */
    private function updateSMSStatus($queueId, $status)
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

$smsQueueSender = new SmsQueueSender(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $emailService,
    $configLoader,
    $viewParser,
    $smsService
);

$smsQueueSender->execute();
