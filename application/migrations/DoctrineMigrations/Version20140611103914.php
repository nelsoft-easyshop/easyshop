<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140611103914 extends AbstractMigration
{
     /*
     *   Schema tables array
     * 
     *   @array('stable_name' => array('create' => "" , 'insert' => ""))
     */

    private $tables;
    
    /*
     *   Database functions
     * 
     *   @array('fx_name' => "")
     */
    
    private $functions;
    
    /*
     *   Database stored procedures
     * 
     *   @array('sp_name' => "")
     */
    
    private $procedure;

    public function __construct(Version $version)
    {   
        parent::__construct($version);
  
        chdir(__DIR__);
        $path = getcwd().'/../../config/migrations/schema/';
        
        $this->tables = require $path.'table.php';
        $this->functions = require $path.'function.php';
        $this->procedures = require $path.'procedure.php';
    }
    
    public function up(Schema $schema)
    {
        foreach($this->tables as $sql_statement){
            if(strlen(trim($sql_statement['create'])) > 0){
                $this->addSql($sql_statement['create']);
            }
           
            if(strlen(trim($sql_statement['insert'])) > 0){
                
                $this->addSql($sql_statement['insert']);
            }
        }
        
        /*
         *   Generate stored procedures
         */
        
        foreach($this->procedures as $sql_statement){
            if(strlen(trim($sql_statement)) > 0){
                $this->addSql($sql_statement);
            }
        }
        
        /*
         *   Generate functions
         */
        
        foreach($this->functions as $sql_statement){
            if(strlen(trim($sql_statement)) > 0){
               $this->addSql($sql_statement);
            }
        }

    }

    public function down(Schema $schema)
    {
        foreach($this->tables as $table_name => $sql_statement){
             $this->addSql("DROP TABLE IF EXISTS `".$table_name."`;");
        }
        
        foreach($this-procedures as $procedure_name => $sql_statement){
             $this->addSql("DROP PROCEDURE IF EXISTS `".$procedure_name."`;");
        }
        
        foreach($this-functions as $function_name => $sql_statement){
             $this->addSql("DROP FUNCTION IF EXIST `".$function_name."`;");
        }
    }
}
