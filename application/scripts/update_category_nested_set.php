<?php 
    
    $configDatabase = require dirname(__FILE__). '/../config/param/database.php';

    try{
        $connectionString = "mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'];
        $dbConnection = new PDO($connectionString, $configDatabase['user'] , $configDatabase['password']);
    }
    catch(PDOException $e){
        echo "Failed to connect to DB: " . $e->getMessage();
        die;
    }
  
    $preparedStatement = $dbConnection->prepare("SELECT id_cat, parent_id FROM es_cat ORDER BY id_cat");
    $preparedStatement->execute();
    $allCategories = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    
 
    /**
     * Translate the categories to address the problem with spaces in between the category ids
     * For the nested set to work, the ids must appear sequentially without breaks.
     */
    $translatedCategories = [];
    $categoryOneToOneMap = [];
    
    $count = 1;
    foreach($allCategories as $category){   
        $translatedId = $count;
        $translatedCategories[$category['id_cat']] = ['translated_id' => $translatedId, 
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
    foreach($translatedCategories as $index => $translatedCategory){
        $translatedParentId = 0;
        if(isset($translatedCategories[$translatedCategory['original_parent_id']])){
            $translatedParentId = $translatedCategories[$translatedCategory['original_parent_id']]['translated_id'];
        }  
        $translatedCategories[$index]['translated_parent_id'] = $translatedParentId;
    }

    $categoriesGroupedByParent = [];
    $count = 0;
    foreach($translatedCategories as $category){
        $parentId = $category['translated_parent_id'];
        $categoryId = $category['translated_id'];    
        if(!array_key_exists($parentId, $categoriesGroupedByParent)){
            $categoriesGroupedByParent[$parentId] = [];
        }
        $categoriesGroupedByParent[$parentId][] = $categoryId;
    }


    /**
     * Unset the root category from the children of root
     * 
     */
    $position = array_search(1, $categoriesGroupedByParent[1]);
    unset($categoriesGroupedByParent[1][$position]);

    try{
        $nestedSetTranformer = new treeTransformer($categoriesGroupedByParent);
        $nestedSetTranformer->traverse(1);
        $pdoData = $nestedSetTranformer->getPDOdata();
    }
    catch(Exception $e){
        exit($e->getMessage());
    }
    
    
    $dropTemporaryTableStatement = $dbConnection->prepare("DROP TEMPORARY TABLE IF EXISTS temp_category_nested_set;");  
    $dropTemporaryTableStatement->execute();
    $temporaryTableCreateQuery = "CREATE TEMPORARY TABLE temp_category_nested_set
                                        (`id_category_nested_set` int(11) NOT NULL,
                                        `left` int(11) NOT NULL DEFAULT '0',
                                        `right` int(11) NOT NULL DEFAULT '0')";
    $preparedStatement = $dbConnection->prepare($temporaryTableCreateQuery);                        
    $preparedStatement->execute();

    
    $preparedStatement = $dbConnection->prepare($pdoData['PDOquery']);
    for($count = 0; $count < count($pdoData['bindParameters']) ; $count++ ){
        $parameter =  $pdoData['bindParameters'][$count];
        $index = $count + 1;
        $preparedStatement->bindValue($index, $parameter, PDO::PARAM_INT);
    }
    if(!$preparedStatement->execute()){
        print_r($preparedStatement->errorInfo());
    }

    $preparedStatement = $dbConnection->prepare("SELECT * FROM temp_category_nested_set");
    $preparedStatement->execute();
    $temporaryNestedSet = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);;
    
    $clearNestedSetTableStatement = $dbConnection->prepare("DELETE FROM es_category_nested_set WHERE 1;");  
    $clearNestedSetTableStatement->execute();
    
    $finalInsert = "INSERT INTO es_category_nested_set (`id_category_nested_set`, `left`, `right`, `original_category_id`) VALUES ";
    $bindParamaters = [];

    foreach($temporaryNestedSet as $nestedSet){
        $finalInsert .= '(?,?,?,?),';
        $bindParamaters[] = $nestedSet['id_category_nested_set'];
        $bindParamaters[] = $nestedSet['left'];
        $bindParamaters[] = $nestedSet['right'];
        $bindParamaters[] = $categoryOneToOneMap[$nestedSet['id_category_nested_set']];
    }
    $finalInsert = rtrim($finalInsert, ',');
    $finalInsertstatement = $dbConnection->prepare($finalInsert);  
    
    for($count = 0; $count < count($bindParamaters) ; $count++ ){
        $parameter = $bindParamaters[$count];
        $index = $count + 1;
        $finalInsertstatement->bindValue($index, $parameter, PDO::PARAM_INT);
    }
    if(!$finalInsertstatement->execute()){
        print_r($finalInsertstatement->errorInfo());
    }
    
    $dropTemporaryTableStatement = $dbConnection->prepare("DROP TEMPORARY TABLE IF EXISTS temp_category_nested_set;");  
    echo 'Nested set table successfully generated.';

    
    /**
     * @class   treeTransformer
     * @author  Original Paul Houle, Matthew Toledo
     * @created 2008-11-04
     * @url     http://gen5.info/q/2008/11/04/nested-sets-php-verb-objects-and-noun-objects/
     * 
     * Refactored to use PDO and adhere to coding standards
     *
     */
    class treeTransformer 
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
            if(!is_array($list)){
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
                foreach($children as $child){
                    $this->traverse($child);
                }
            }
            $right = $this->count;
            $this->count++;
            $this->write($left,$right,$startingId);
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
        private function write($left,$right,$categoryId ) 
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

            $pdoData['PDOquery'] = $partialSql . trim($this->PDOquery,',');
            $pdoData['rawQuery'] = $partialSql . trim($this->rawQuery,',');
            $pdoData['bindParameters'] =  $this->bindParameters;
            return $pdoData;
        }

    }



