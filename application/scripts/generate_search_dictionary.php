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

    $productsWithBrandsSql = "SELECT 
                            es_brand.name as brandName,
                            COUNT(es_brand.name) as brandOccurences
                        FROM es_product 
                        INNER JOIN es_brand 
                            ON es_product.brand_id = es_brand.id_brand
                        INNER JOIN es_member
                            ON es_member.is_active = :memberActive
                            AND es_member.id_member = es_product.member_id
                        WHERE
                            es_product.is_delete = :deleteStatus AND 
                            es_product.is_draft = :draftStatus AND
                            es_product.brand_id <> :customBrand
                        GROUP BY es_product.brand_id
                        HAVING brandOccurences >= :brandOccurenceLimit
    ";

    
    $preparedStatement = $dbConnection->prepare($productsWithBrandsSql);
    $memberStatus = EasyShop\Entities\EsMember::DEFAULT_ACTIVE;
    $deleteStatus = EasyShop\Entities\EsProduct::ACTIVE;
    $draftStatus = EasyShop\Entities\EsProduct::ACTIVE;
    $customBrand = EasyShop\Entities\EsBrand::CUSTOM_CATEGORY_ID;
    $brandOccurenceLimit = $dictionary->getOccurencesLimit();

    $preparedStatement->bindParam("memberActive", $memberStatus);
    $preparedStatement->bindParam("deleteStatus", $deleteStatus);
    $preparedStatement->bindParam("draftStatus", $draftStatus);
    $preparedStatement->bindParam("customBrand", $customBrand);
    $preparedStatement->bindParam("brandOccurenceLimit", $brandOccurenceLimit);
    $preparedStatement->execute();
    $brands = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    
    var_dump($brands);
    
    foreach($brands as $brand){
        $dictionary->insertWordIntoDictionary($brand['brandName'],$brand['brandOccurences'] );
    }
    
    print_r($dictionary->getDictionary());
    
class Dictionary
{

    const WORD_MIN_LENGTH = 2;
    
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
    
    public function getOccurencesLimit()
    {
        return $this->occurencesMinimum;
    }

    public function insertWordIntoDictionary($word, $sqlOccurences = null)
    {
        if(strlen(trim($word)) >= self::WORD_MIN_LENGTH){
            $word = htmlspecialchars($word);
            $word = strtolower($word);
            $word = preg_replace('/[^A-Za-z0-9\ ]/', '', $word);
            if(isset($this->dictionary[$word])){
                $occurences = $this->dictionary[$word];
                if($sqlOccurences === null){
                    $occurences++;
                }
                else{   
                    $occurences = $occurences + $sqlOccurences;
                }
                $this->dictionary[$word] = $occurences;
            }
            else{
                $occurences = $sqlOccurences === null ? 1 : $sqlOccurences;
                $this->dictionary[$word] = $occurences;
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

