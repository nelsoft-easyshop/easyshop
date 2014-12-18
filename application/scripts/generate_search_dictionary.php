<?php
    /**
     * Generates the search dictionary table
     *
     * @author sam gavinio <samgavinio@easyshop.ph>
     */
    include_once  __DIR__.'/bootstrap.php';
    $CI =& get_instance();
        
    try{
        $connectionString = $CI->db->hostname;
        $dbConnection = new PDO($connectionString, $CI->db->username , $CI->db->password);
    }
    catch(PDOException $e){
        echo "Failed to connect to DB: " . $e->getMessage();
        die;
    }
        
    $productCountSql = "SELECT 
                            es_product.name as productName
                        FROM es_product 
                        INNER JOIN es_member 
                            ON es_member.id_member = es_product.member_id AND 
                            es_member.is_active = :memberActive
                        WHERE
                            es_product.is_delete = :deleteStatus AND 
                            es_product.is_draft = :draftStatus
    ";

    $preparedStatement = $dbConnection->prepare($productCountSql);
    $memberStatus = EasyShop\Entities\EsMember::DEFAULT_ACTIVE;
    $deleteStatus = EasyShop\Entities\EsProduct::ACTIVE;
    $draftStatus = EasyShop\Entities\EsProduct::ACTIVE;

    $preparedStatement->bindParam("memberActive", $memberStatus);
    $preparedStatement->bindParam("deleteStatus", $deleteStatus);
    $preparedStatement->bindParam("draftStatus", $draftStatus);
    $preparedStatement->execute();
    $activeProducts = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

    $dictionary = new Dictionary();
    $dictionary->initialize();
    
    foreach($activeProducts as $product){
        $words = explode(" ",$product['productName']);
        $runningWord = '';
        foreach($words as $index => $word){
            $dictionary->insertWordIntoDictionary($word);
            $runningWord .= $word.' ';
            if($index > 0){
                $dictionary->insertWordIntoDictionary(rtrim($runningWord));
            }
        }
    }


 
    
    print_r($dictionary->getDictionary());
    
class Dictionary
{

    const WORD_MIN_LENGTH = 3;
    
    const DEV_MIN_OCCURENCES = 3;
    
    const PROD_MIN_OCCURENCES = 20;
        
    private $dictionary;
    
    private $occurencesMinimum;
    
    public function initialize()
    {
        $this->dictionary = [];
        if(ENVIRONMENT === 'production'){
            $this->occurencesMinimum = self::PROD_MIN_OCCURENCES;
        }
        else{
            $this->occurencesMinimum = self::DEV_MIN_OCCURENCES;
        }
    }

    public function insertWordIntoDictionary($word)
    {
        if(strlen(trim($word)) >= self::WORD_MIN_LENGTH){
            $word = htmlspecialchars($word);
            $word = strtolower($word);
            $word = preg_replace('/[^A-Za-z0-9\ ]/', '', $word);
            if(isset($this->dictionary[$word])){
                $occurences = $this->dictionary[$word];
                $occurences++;
                $this->dictionary[$word] = $occurences;
            }
            else{
                $this->dictionary[$word] = 1;
            }
        }
    }
    
    public function getDictionary($isRaw = false)
    {
        foreach($this->dictionary as $index=>$word){
            if($word < $this->occurencesMinimum){
                unset($this->dictionary[$index]);
            }
        }
        return  $this->dictionary;
    }

}

