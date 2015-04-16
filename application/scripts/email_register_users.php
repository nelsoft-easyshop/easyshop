<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();


use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class EmailRegisterUser extends ScriptBaseClass
{
    private $connection;
    private $emailService;
    private $dateQuery;

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
        $this->emailService = $emailService;
        $this->dateQuery = [
            'this_date' => date("Y-m-d H:i:s"),
            'past_date' => date("Y-m-d H:i:s", strtotime("-7 days"))
        ];
    }

    public function execute()
    {
        $emailData = $this->getEmailData();
        $this->constructSendMail($emailData);
    }

    /**
     * Generate attachment content
     * @return string
     */
    private function constructAttachment()
    {
        $userLists = $this->getUserList();
        $csvHeader = [
            'USERNAME',
            'CONTACT NO',
            'EMAIL',
            'NICKNAME',
            'FULLNAME',
            'DATE CREATED'
        ];
        $csvData = implode(', ', $csvHeader) . PHP_EOL;
        foreach ($userLists as $user) {
            $csvData .= implode(', ', $user) . PHP_EOL;
        }

        return $csvData;
    }

    /**
     * Get all user list
     * @return array
     */
    public function getUserList()
    {
        $getUsersQuery = "
            SELECT username, contactno, email, nickname, fullname, datecreated
            FROM es_member
            WHERE datecreated BETWEEN :past_date AND :this_date

            UNION
            
            SELECT '','',email,'','',datecreated
            FROM es_subscribe
            WHERE datecreated BETWEEN :past_date AND :this_date 
            
            ORDER BY datecreated
        ";

        $getUserList = $this->connection->prepare($getUsersQuery);
        $getUserList->bindValue("past_date", $this->dateQuery['past_date'], PDO::PARAM_STR);
        $getUserList->bindValue("this_date", $this->dateQuery['this_date'], PDO::PARAM_STR);
        $getUserList->execute();
        $userList = $getUserList->fetchAll(PDO::FETCH_ASSOC);

        return $userList;
    }

    /**
     * Construct and send email
     * @param  array $emailData
     */
    private function constructSendMail($emailData)
    {
        $emailResult = $this->emailService
                            ->setRecipient($emailData['recipient'])
                            ->setSubject($emailData['subject'])
                            ->setMessage($emailData['message'])
                            ->addAttachment(
                                $this->constructAttachment(),
                                $emailData['attachment_filename'],
                                $emailData['attachment_filetype']
                            )
                            ->sendMail();
    }

    /**
     * Get config email data
     * @return array
     */
    private function getEmailData()
    {
        return [
            'subject' => 'New members for Easyshop.ph',
            'message' => '',
            'attachment_filename' => 'registered-'.date("M-j-Y").'.csv',
            'attachment_filetype' => 'application/vnd.ms-excel',
            'recipient' => [
                'samgavinio@easyshop.ph'
            ]
        ];
    }
}

$pointChecker  = new EmailRegisterUser(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $emailService,
    $configLoader,
    $viewParser
);

$pointChecker->execute();
