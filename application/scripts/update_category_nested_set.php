<?php

include_once  __DIR__.'/bootstrap.php';

$CI =& get_instance();
$configLoader = $CI->kernel->serviceContainer['config_loader'];
$emailService = $CI->kernel->serviceContainer['email_notification'];
$viewParser = new \CI_Parser();

use EasyShop\Script\ScriptBaseClass as ScriptBaseClass;
use EasyShop\Entities\EsCat as EsCat;
use TreeTransformer as TreeTransformer;

class UpdateCategoryNestedSet extends ScriptBaseClass
{
    private $connection;

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
    }

    /**
     * Execute script
     */
    public function execute()
    {
        $allCategories = $this->getAllCategory();

        /**
         * Translate the categories to address the problem with spaces in between the category ids
         * For the nested set to work, the ids must appear sequentially without breaks.
         */
        $translatedCategories = [];
        $categoryOneToOneMap = [];
        $count = 1;
        foreach ($allCategories as $category) {
            $translatedId = $count;
            $translatedCategories[$category['id_cat']] = [
                'translated_id' => $translatedId,
                'original_id' => $category['id_cat'],
                'original_parent_id' => $category['parent_id'],
            ];
            $categoryOneToOneMap[$translatedId] =  $category['id_cat'];
            $count++;
        }

        /**
         * Insert translated parent Id only after generating the translated table
         * in order to take into account cases where a category appears in the
         * result before it's parent
         */
        foreach ($translatedCategories as $index => $translatedCategory) {
            $translatedParentId = 0;
            if (isset($translatedCategories[$translatedCategory['original_parent_id']])) {
                $translatedParentId = $translatedCategories[$translatedCategory['original_parent_id']]['translated_id'];
            }
            $translatedCategories[$index]['translated_parent_id'] = $translatedParentId;
        }

        $categoriesGroupedByParent = [];
        $count = 0;
        foreach ($translatedCategories as $category) {
            $parentId = $category['translated_parent_id'];
            $categoryId = $category['translated_id'];
            if (!array_key_exists($parentId, $categoriesGroupedByParent)) {
                $categoriesGroupedByParent[$parentId] = [];
            }
            $categoriesGroupedByParent[$parentId][] = $categoryId;
        }

        /**
         * Unset the root category from the children of root
         */
        $position = array_search(EsCat::ROOT_CATEGORY_ID, $categoriesGroupedByParent[EsCat::ROOT_CATEGORY_ID]);
        unset($categoriesGroupedByParent[EsCat::ROOT_CATEGORY_ID][$position]);

        $nestedSetTranformer = new TreeTransformer($categoriesGroupedByParent);
        $nestedSetTranformer->traverse(EsCat::ROOT_CATEGORY_ID);
        $pdoData = $nestedSetTranformer->getPDOdata();

        $this->createTempTable();

        $preparedStatement = $this->connection->prepare($pdoData['PDOquery']);
        for ($count = 0; $count < count($pdoData['bindParameters']); $count++) {
            $parameter =  $pdoData['bindParameters'][$count];
            $index = $count + 1;
            $preparedStatement->bindValue($index, $parameter, PDO::PARAM_INT);
        }
        $preparedStatement->execute();

        $temporaryNestedSet = $this->selectAllTempTableData();
        $this->emptyCategoryNestedSet();

        $insertToNestedTableQuery = "INSERT INTO es_category_nested_set (`id_category_nested_set`, `left`, `right`, `original_category_id`) VALUES ";
        $bindParamaters = [];
        foreach ($temporaryNestedSet as $nestedSet) {
            $insertToNestedTableQuery .= '(?,?,?,?),';
            $bindParamaters[] = $nestedSet['id_category_nested_set'];
            $bindParamaters[] = $nestedSet['left'];
            $bindParamaters[] = $nestedSet['right'];
            $bindParamaters[] = $categoryOneToOneMap[$nestedSet['id_category_nested_set']];
        }
        $insertToNestedTableQuery = rtrim($insertToNestedTableQuery, ',');
        $finalInsertstatement = $this->connection->prepare($insertToNestedTableQuery);
        
        for ($count = 0; $count < count($bindParamaters); $count++) {
            $parameter = $bindParamaters[$count];
            $index = $count + 1;
            $finalInsertstatement->bindValue($index, $parameter, PDO::PARAM_INT);
        }
        $finalInsertstatement->execute();

        $this->dropTempTable();
        echo "\nNested set table successfully generated.\n\n";
    }

    /**
     * Get all category in es_cat table
     * @return array
     */
    private function getAllCategory()
    {
        $getAllCategory = $this->connection->prepare("SELECT id_cat, parent_id FROM es_cat ORDER BY id_cat");
        $getAllCategory->execute();
        $allCategories = $getAllCategory->fetchAll(PDO::FETCH_ASSOC);

        return $allCategories;
    }

    /**
     * Create temp table
     */
    private function createTempTable()
    {
        $this->dropTempTable();
        $temporaryTableCreateQuery = "CREATE TEMPORARY TABLE temp_category_nested_set
                                            (`id_category_nested_set` int(11) NOT NULL,
                                            `left` int(11) NOT NULL DEFAULT '0',
                                            `right` int(11) NOT NULL DEFAULT '0')";
        $preparedStatement = $this->connection->prepare($temporaryTableCreateQuery);
        $preparedStatement->execute();
    }

    /**
     * drop temp table
     */
    private function dropTempTable()
    {
        $dropTemporaryTableStatement = $this->connection->prepare("DROP TEMPORARY TABLE IF EXISTS temp_category_nested_set;");
        $dropTemporaryTableStatement->execute();
    }

    /**
     * Select all data in temp table
     * @return array
     */
    private function selectAllTempTableData()
    {
        $preparedStatement = $this->connection->prepare("SELECT * FROM temp_category_nested_set");
        $preparedStatement->execute();
        $temporaryNestedSet = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

        return $temporaryNestedSet;
    }

    /**
     * Empty category_nested_set table
     */
    private function emptyCategoryNestedSet()
    {
        $clearNestedSetTableStatement = $this->connection->prepare("DELETE FROM es_category_nested_set WHERE 1;");
        $clearNestedSetTableStatement->execute();
    }
}

$updateCategoryNestedSet = new UpdateCategoryNestedSet(
    $CI->db->hostname,
    $CI->db->username,
    $CI->db->password,
    $emailService,
    $configLoader,
    $viewParser
);

$updateCategoryNestedSet->execute();

/**
 * @class   TreeTransformer
 * @author  Original Paul Houle, Matthew Toledo
 * @created 2008-11-04
 * @url     http://gen5.info/q/2008/11/04/nested-sets-php-verb-objects-and-noun-objects/
 *
 * Refactored to use PDO and adhere to coding standards
 *
 */
class TreeTransformer
{
    /**
     * Index counter
     *
     * @var integer
     */
    private $count;
    
    
    /**
     * The nested set list array
     *
     * @var integer[]
     */
    private $list;

    /**
     * The PDO query with parameter placeholders
     *
     * @var string
     */
    private $PDOquery;

    /**
     * The raw query
     *
     * @var string
     */
    private $rawQuery;

    /**
     * Array of bind parameters
     *
     * @var mixed
     */
    private $bindParameters;

    /**
     * Initialize the class
     *
     * @param array $list
     */
    public function __construct($list)
    {
        if (!is_array($list)) {
            throw new Exception("First parameter should be an array. Instead, it was type '".gettype($list)."'");
        }
        $this->count = 1;
        $this->list= $list;
        $this->PDOquery = '';
        $this->bindParameters = [];
        $this->rawQuery = '';
    }

    /**
     * Traverses the list begining with $startId and
     * stores it into the nested set table
     *
     * @param integer $startingId
     */
    public function traverse($startingId)
    {
        $left = $this->count;
        $this->count++;

        $children = $this->getChildren($startingId);

        if ($children) {
            foreach ($children as $child) {
                $this->traverse($child);
            }
        }
        $right = $this->count;
        $this->count++;
        $this->write($left, $right, $startingId);
    }

    
    /**
     * Returns children of a certain category
     *
     * @param integer[]
     */
    private function getChildren($categoryId)
    {
        return isset($this->list[$categoryId]) ? $this->list[$categoryId] : false;
    }

    /**
     * Inserts a node into the nested set table
     *
     * @param integer $left
     * @param integer $right
     * @param integer $categoryId
     */
    private function write($left, $right, $categoryId)
    {
        $left = (int)$left;
        $right = (int)$right;
        
        $this->PDOquery .= '( ?, ?, ?),';
        $this->bindParameters[] = $categoryId;
        $this->bindParameters[] = $left;
        $this->bindParameters[] = $right;
        $this->rawQuery .= '('.$categoryId.','.$left.','. $right.'),';
    }
    
    /**
     * Returns the sql insert query
     *
     * @return string
     */
    public function getPDOdata()
    {
        $pdoData = [];
        $partialSql = "INSERT INTO `temp_category_nested_set` (`id_category_nested_set`,`left`,`right`) VALUES ";

        $pdoData['PDOquery'] = $partialSql . trim($this->PDOquery, ',');
        $pdoData['rawQuery'] = $partialSql . trim($this->rawQuery, ',');
        $pdoData['bindParameters'] =  $this->bindParameters;
        return $pdoData;
    }
}
