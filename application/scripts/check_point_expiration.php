<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$pointTracker = $CI->kernel->serviceContainer['point_tracker'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;
use EasyShop\Entities\EsPointType as EsPointType;

class CheckPointExpiration extends ScriptBaseClass
{
    private $connection;
    private $pointTracker;

    /**
     * Constructor
     * @param string                                   $hostName
     * @param string                                   $dbUsername
     * @param string                                   $dbPassword
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     * @param EasyShop\PointTracker\PointTracker       $pointTracker
     */
    public function __construct(
        $hostName,
        $dbUsername,
        $dbPassword,
        $emailService,
        $configLoader,
        $viewParser,
        $pointTracker
    ) {
        parent::__construct($emailService, $configLoader, $viewParser);

        $this->connection = new PDO(
            $hostName,
            $dbUsername,
            $dbPassword
        );
        $this->pointTracker = $pointTracker;
    }

    /**
     * Execute script
     */
    public function execute()
    {
        echo "\nScanning of data started (".date('M-d-Y h:i:s A').") \n \n";
        $pointsCollection = $this->getAllExpiredPoints();

        $updatedSuccess = 0;
        $updatedFail = 0;
        foreach ($pointsCollection as $point) {
            $memberId = (int) $point['member_id'];
            $currentPoint = (float) $point['point'];
            echo "Updating Points - Member id: " . $memberId . " --->";
            $isUpdated = $this->pointTracker->spendUserPoint($memberId, EsPointType::TYPE_EXPIRED, $currentPoint);
            echo $isUpdated ? "\t\033[0;32m[UPDATED]\033[0m\n" : "\t\033[0;31m[FAILED]\033[0m\n";
            $isUpdated ? $updatedSuccess++ : $updatedFail++ ;
        }

        echo "\nScanning of data ended (".date('M-d-Y h:i:s A').") \n \n";
        echo "Success: " . $updatedSuccess . "\n";
        echo "Failed: " . $updatedFail . "\n\n";
        echo count($pointsCollection)." ROWS SCANNED! \n \n";
    }

    /**
     * Get all expired points
     * @return array
     */
    private function getAllExpiredPoints()
    {
        $selectPointsQuery = "
            SELECT 
                `member_id`, `point`
            FROM
                `es_point`
            WHERE
                '".date('Y-m-d H:i:s')."' >= `expiration_date`
            AND `point` > 0
        ";

        $selectPoints = $this->connection->prepare($selectPointsQuery);
        $selectPoints->execute();
        $points = $selectPoints->fetchAll(PDO::FETCH_ASSOC);

        return count($points) > 0 ? $points : [];
    }
}

$pointChecker  = new CheckPointExpiration(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $emailService,
    $configLoader,
    $viewParser,
    $pointTracker
);

$pointChecker->execute();
