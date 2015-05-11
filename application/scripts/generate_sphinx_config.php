<?php

include_once  __DIR__.'/bootstrap.php';
$CI =& get_instance();
$emailService = $CI->kernel->serviceContainer['email_notification'];
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;

class GenerateSphinxConfiguration extends ScriptBaseClass
{
    private $sphinxDirectory;
    private $hostname;
    private $dbUsername;
    private $dbPassword;
    private $dbName;

    /**
     * Constructor
     * @param EasyShop\Notifications\EmailNotification $emailService
     * @param EasyShop\ConfigLoader\ConfigLoader       $configLoader
     * @param \CI_Parser                               $viewParser
     */
    public function __construct(
        $hostname,
        $dbUsername,
        $dbPassword,
        $dbName,
        $emailService,
        $configLoader,
        $viewParser
    ) {
        parent::__construct($emailService, $configLoader, $viewParser);

        $this->hostname = $this->parseHostname($hostname);
        $this->dbUsername = $dbUsername;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }

    /**
     * Execute function to generate sphinx config
     */
    public function execute()
    {
        if ($this->hostname === null) {
            die("\nError: hostname cannot be found\n\n");
        }

        $configString = '
            source products { 
                    type = mysql
                    sql_host = '.$this->hostname.'
                    sql_user = '.$this->dbUsername.'
                    sql_pass = '.$this->dbPassword.'
                    sql_db = '.$this->dbName.'

                    sql_query_range = SELECT MIN(id_product), MAX(id_product) FROM es_product

                    sql_range_step = 1000

                    sql_query = SELECT \
                                    es_product.id_product, \
                                    es_product.id_product as productId,\
                                    es_product.name, \
                                    es_product.search_keyword, \
                                    es_member.store_name \
                                FROM es_product \
                                    INNER JOIN es_member ON  es_member.id_member = es_product.member_id \
                                                        AND es_member.is_active != 0 \
                                WHERE es_product.id_product >= $start AND es_product.id_product <= $end \
                                    AND es_product.is_delete = 0 AND es_product.is_draft = 0 
                                    
                    sql_attr_uint = productid      
            }

            source suggestions {   

                type = mysql
                sql_host = '.$this->hostname.'
                sql_user = '.$this->dbUsername.'
                sql_pass = '.$this->dbPassword.'
                sql_db = '.$this->dbName.'

                sql_query_range = SELECT MIN(id_keywords), MAX(id_keywords) FROM es_keywords

                sql_range_step = 1000

                sql_query = SELECT \
                                es_keywords.id_keywords, \
                                es_keywords.keywords, \
                                es_keywords.keywords as keywordAttr, \
                                es_keywords.occurences as occurenceAttr \
                            FROM es_keywords \
                            WHERE es_keywords.id_keywords >= $start AND es_keywords.id_keywords <= $end
                                
                sql_attr_uint = occurenceAttr      
                sql_attr_string = keywordAttr   

            }

            source products_delta : products
            {
                sql_query_range = SELECT MIN(id_product), MAX(id_product) FROM es_product \
                                    WHERE createddate >= CONCAT(CURDATE() , " 00:00:00" ) \
                                              OR lastmodifieddate >= CONCAT(CURDATE() , " 00:00:00" )

                sql_range_step = 1000

                sql_query = SELECT \
                    es_product.id_product, \
                    es_product.id_product as productId,\
                    es_product.name, \
                    es_product.search_keyword, \
                    es_member.store_name \
                FROM es_product \
                    INNER JOIN es_member ON  es_member.id_member = es_product.member_id \
                                        AND es_member.is_active != 0 \
                WHERE es_product.id_product >= $start AND es_product.id_product <= $end \
                    AND es_product.is_delete = 0 AND es_product.is_draft = 0 \
                    AND ( es_product.createddate >= CONCAT(CURDATE() , " 00:00:00" ) \
                                              OR es_product.lastmodifieddate >= CONCAT(CURDATE() , " 00:00:00" ) )
            }

            source users {
                type = mysql
                sql_host = '.$this->hostname.'
                sql_user = '.$this->dbUsername.'
                sql_pass = '.$this->dbPassword.'
                sql_db = '.$this->dbName.'
                sql_query_range = SELECT MIN(id_member), MAX(id_member) FROM es_member
                sql_range_step = 1000

                sql_query = SELECT \
                                es_member.id_member, \
                                es_member.id_member as memberId,\
                                COALESCE(NULLIF(es_member.store_name, ""), es_member.username) as store_name\
                            FROM es_member \
                            WHERE es_member.id_member >= $start AND es_member.id_member <= $end \
                                AND es_member.is_banned = 0 AND es_member.is_active = 1

                sql_attr_uint = memberid
            }

            source users_delta : users
            {
                sql_query_range = SELECT MIN(id_member), MAX(id_member) FROM es_member \
                                    WHERE datecreated >= CONCAT(CURDATE() , " 00:00:00" ) \
                                              OR lastmodifieddate >= CONCAT(CURDATE() , " 00:00:00" )

                sql_range_step = 1000

                sql_query = SELECT \
                                es_member.id_member, \
                                es_member.id_member as memberId,\
                                COALESCE(NULLIF(es_member.store_name, ""), es_member.username) as store_name\
                            FROM es_member \
                            WHERE es_member.id_member >= $start AND es_member.id_member <= $end \
                                AND es_member.is_banned = 0 AND es_member.is_active = 1 \
                                AND ( \
                                    es_member.datecreated >= CONCAT(CURDATE(), " 00:00:00" ) \
                                    OR es_member.lastmodifieddate >= CONCAT(CURDATE(), " 00:00:00") \
                                )
            }

            index products {

                source = products

                path = '.$this->sphinxDirectory.'/data/products/main

                wordforms = '.$this->sphinxDirectory.'/etc/wordforms.txt 

                min_word_len = 3

                min_infix_len = 3
            }

            index products_delta
            {
                source = products_delta

                path = '.$this->sphinxDirectory.'/data/products/delta

                wordforms = '.$this->sphinxDirectory.'/etc/wordforms.txt 

                min_word_len = 3

                min_infix_len = 3
            }

            index suggestions {

                source = suggestions

                path = '.$this->sphinxDirectory.'/data/suggestions

                morphology = metaphone
                
                min_word_len = 3

                min_infix_len = 2
            } 

            index users {

                source = users

                path = '.$this->sphinxDirectory.'/data/users/main

                morphology = metaphone
                
                min_word_len = 2

                min_infix_len = 2
            }

            index users_delta
            {
                source = users_delta

                path = '.$this->sphinxDirectory.'/data/users/delta

                min_word_len = 2

                min_infix_len = 2
            }

            searchd {
            
                max_filter_values = 16384

                log = '.$this->sphinxDirectory.'/logs/searchd.log
                query_log = '.$this->sphinxDirectory.'/logs/query.log
                pid_file = '.$this->sphinxDirectory.'/logs/searchd.pid

                listen = localhost:9312

            }
        ';
        $file = $this->sphinxDirectory.'/etc/sphinx.conf';
        file_put_contents($file, $configString);
        echo "\nsphinx.conf has been generated in /sphinx/etc/sphinx.conf\n\n";
    }

    /**
     * Parse hostname and set into hostname variable
     * @param  string $hostname
     * @return string
     */
    private function parseHostname($hostname)
    {
        $tempHostname = null;
        $hostArray = explode(';', $hostname);
        foreach ($hostArray as $hostField) {
            $keyLocation = strpos($hostField, 'host=');
            if ($keyLocation !== -1) {
                $tempHostname = substr($hostField, $keyLocation + strlen('host='));
                break;
            }
        }

        return $tempHostname;
    }

    /**
     * Set sphinx directory location
     * @param  string $directory
     * @return $this
     */
    public function setSphinxDirectory($directory)
    {
        $this->sphinxDirectory = $directory;

        return $this;
    }
}

$generateSphinxConfiguration  = new GenerateSphinxConfiguration(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $CI->db->database,
    $emailService,
    $configLoader,
    $viewParser
);

$generateSphinxConfiguration->setSphinxDirectory(__DIR__.'/../../sphinx')
                            ->execute();
