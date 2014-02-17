<?php



/**
 * Member Category Preference model object
 *
 * @author Czar Pino
 */
class MemberCategoryPreference extends CI_Model
{
    /**** Entity attributes ****/
    
    /**
     * Primary key
     * 
     * @var int
     */
    public $id;
    
    /**
     * Id of corresponding member
     * 
     * @var int
     */
    public $member_id;
    
    /**
     * Id of corresponding category
     * 
     * @var int
     */
    public $cat_id;
    
    /**
     * Degree of member's preference for category
     * 
     * @var int 
     */
    public $value;
    
    /**** Entity attributes END ****/
    
    /**
     * Table name
     * 
     * @var string
     */
    private $table = "es_member_category_preference";
    
    /**
     * Table columns
     * 
     * @var array
     */
    private $columns = array ("id", "member_id", "cat_id", "value");
    
    /**
     * PDO instance
     * 
     * @var PDO
     */
    private $pdo = NULL;
    
    /**
     * PDO statment for inserting
     * 
     * @var PDOStatement
     */
    private $insertStmt = NULL;
    
    /**
     * PDO statment for updating
     * 
     * @var PDOStatement
     */
    private $updateStmt = NULL;
    
    /**
     * Persist current object to database
     * 
     * @return boolean TRUE on success, FALSE otherwise
     */
    public function insert()
    {
        return $this->getInsertStmt()->execute();
    }
    
    /**
     * Persist changes to this object into database
     * 
     * @return boolean TRUE on success, FALSE otherwise
     */
    public function update()
    {
        return $this->getUpdateStmt()->execute();
    }
    
    /**
     * Retrieve MemberCategoryPreference sith the specified 
     * member id and category id
     * 
     * @param int $memberId
     * @param int $categoryId
     * 
     * @return MemberCategoryPreference or FALSE on failure
     */
    public function retrieveByMemberAndCategory($memberId, $categoryId)
    {
        $columns = $this->formatColumns();
        $stmt = $this->getPDO()->prepare("SELECT $columns FROM `es_member_category_preference` " .
                                         "WHERE `$this->table`.`member_id` = :member_id " .
                                         "AND `$this->table`.`cat_id` = :cat_id");
        
        $stmt->bindValue(":member_id", $memberId);
        $stmt->bindValue(":cat_id", $categoryId);
        $stmt->execute();
        
        return $stmt->fetchObject("MemberCategoryPreference");
    }
    
    /**
     * Format all columns into string for query
     * 
     * @return string
     */
    private function formatColumns($columns = NULL)
    {
        $formattedColumns = array ();
        
        if (NULL == $columns) {
            $columns = $this->columns;
        }
        
        foreach ($columns as $column) {
            $formattedColumns[] = "`$this->table`.`$column`";
        }

        return implode(",", $formattedColumns);
    }
    
    /**
     * Retrieve PDO instance
     * 
     * @return PDO
     */
    private function getPDO()
    {
        if (NULL === $this->pdo) {
            $this->pdo = $this->db->conn_id;
        }
        
        return $this->pdo;
    }
    
    /**
     * Retrieve insert PDO statement
     * 
     * @return PDOStatement
     */
    private function getInsertStmt()
    {
        if (NULL === $this->insertStmt) {
            
            // exclude id column
            $columns = $this->formatColumns(array_slice($this->columns, 1));
            $this->insertStmt = $this->getPDO()->prepare(
                    "INSERT INTO `$this->table`($columns) " .
                    "VALUES (:member_id, :cat_id, :value)");
            
            $this->insertStmt->bindParam(":member_id", $this->member_id, PDO::PARAM_INT);
            $this->insertStmt->bindParam(":cat_id", $this->cat_id, PDO::PARAM_INT);
            $this->insertStmt->bindParam(":value", $this->value, PDO::PARAM_INT);
        }
        
        return $this->insertStmt;
    }
    
    /**
     * Retrieve update PDO statement
     * 
     * @return PDOStatement
     */
    private function getUpdateStmt()
    {
        if (NULL === $this->updateStmt) {
            $this->updateStmt = $this->getPDO()->prepare(
                    "UPDATE `$this->table` " .
                    "SET `$this->table`.`member_id` = :member_id, " .
                    "`$this->table`.`cat_id` = :cat_id, " .
                    "`$this->table`.`value` = :value " .
                    "WHERE `$this->table`.`id` = :id");
            
            $this->updateStmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $this->updateStmt->bindParam(":member_id", $this->member_id, PDO::PARAM_INT);
            $this->updateStmt->bindParam(":cat_id", $this->cat_id, PDO::PARAM_INT);
            $this->updateStmt->bindParam(":value", $this->value, PDO::PARAM_INT);
        }
        
        return $this->updateStmt;
    }
    
}
