<?php     
        $baseUrl= "https://www.easyshop.ph/";

        $filelocation = dirname(__FILE__).'/../../web/';
        $configDatabase = require dirname(__FILE__). '/../config/param/database.php';
        
        try{
            $connectionString = "mysql:host=".$configDatabase['host'].";dbname=".$configDatabase['dbname'];
            $dbConnection = new PDO($connectionString, $configDatabase['user'] , $configDatabase['password']);
        }
        catch(PDOException $e){
            echo "Failed to connect to DB: " . $e->getMessage();
            die;
        }

        $xml = new DOMDocument();
        $xmlUrlSet = $xml->createElement("urlset");
        $xmlUrlSet->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );     
    
        $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl, 'priority' => 1, 'changefreq' => 'never'));
        $xmlUrlSet->appendChild( $xmlUrl ); 
    
        $commonURI = array('sell/step1','cart','login','register','faq','policy','terms','contact');
        foreach($commonURI as $url){
            $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl.$url, 'priority' => 0.5, 'changefreq' => 'never'));
            $xmlUrlSet->appendChild( $xmlUrl );
        }

        $xml->appendChild( $xmlUrlSet );  
        $xml->save($filelocation.'sitemap.xml');
   
        $preparedStatement = $dbConnection->prepare("SELECT name, id_cat FROM es_cat WHERE parent_id = 1 AND id_cat != 1 AND is_main = 1");
        $preparedStatement->execute();
        $mainCategories = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

        
        foreach($mainCategories as $mainCategory){

            $xml = new DOMDocument();
            $xmlUrlSet = $xml->createElement("urlset");
            $xmlUrlSet->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
 
            $sql = "SELECT CASE 
                            WHEN `GetFamilyTree` (id_cat) = '' THEN '0,0' 
                            ELSE `GetFamilyTree` (id_cat) 
                        END as `catlist`
                    FROM `es_cat` WHERE id_cat != 1 AND id_cat = :cat_id";
                  
            $preparedStatement = $dbConnection->prepare($sql);
            $preparedStatement->bindParam(':cat_id', $mainCategory['id_cat'], PDO::PARAM_INT);
            $preparedStatement->execute();
            $subCategoryList = $preparedStatement->fetch(PDO::FETCH_ASSOC)['catlist'];      
            $parameterArray = explode(",",  $subCategoryList);
            $sql = "SELECT CONCAT('category/',slug) as url FROM es_cat WHERE id_cat IN ";
            $qmarks = implode(',', array_fill(0, count($parameterArray), '?'));
            $sql  = $sql.'('.$qmarks.')';
            
            $preparedStatement = $dbConnection->prepare($sql);
            for($count = 0; $count < count($parameterArray) ; $count++ ){
                $parameter =  $parameterArray[$count];
                $index = $count + 1;
                $preparedStatement->bindValue($index, $parameter, PDO::PARAM_INT);
            }

            $preparedStatement->execute();
            $subCategories = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($subCategories as $subCategory){
                $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl.$subCategory['url'], 'priority' => 0.5, 'changefreq' => 'monthly'));
                $xmlUrlSet->appendChild( $xmlUrl ); 
            }

            $sql="SELECT CONCAT('item/',slug) as url FROM es_product WHERE cat_id IN ";
            $qmarks = implode(',', array_fill(0, count($parameterArray), '?'));
            $sql  = $sql.'('.$qmarks.')  AND is_draft = 0 AND is_delete = 0';
            $preparedStatement = $dbConnection->prepare($sql);
            for($count = 0; $count < count($parameterArray) ; $count++ ){
                $parameter =  $parameterArray[$count];
                $index = $count + 1;
                $preparedStatement->bindValue($index, $parameter, PDO::PARAM_INT);
            }
            $preparedStatement->execute();
            $products = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach($products as $product){
                $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl.$product['url'], 'priority' => 0.5, 'changefreq' => 'weekly'));
                $xmlUrlSet->appendChild( $xmlUrl );
            }
       

             $xml->appendChild( $xmlUrlSet );  
             $mainCategory['name'] = url_clean($mainCategory['name']);
             $xml->save($filelocation.'sitemap-'.strtolower($mainCategory['name']).'.xml');     
        }

        #GENERATE VENDORS XML
        $xml = new DOMDocument();
        $xmlUrlSet = $xml->createElement("urlset");
        $xmlUrlSet->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
        

        $preparedStatement = $dbConnection->prepare("SELECT slug as url FROM es_member");
        $preparedStatement->execute();
        $users = $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($users as $user){
            $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl.$user['url'], 'priority' => 0.5, 'changefreq' => 'monthly'));
            $xmlUrlSet->appendChild( $xmlUrl ); 
        }
   
        $xml->appendChild( $xmlUrlSet );
        $xml->save($filelocation.'sitemap-vendor.xml');
        
        $filelist = array();
        
        #GENERATE SITEMAP INDEX AND COMPRESS SITEMAPS
        $xml = new DOMDocument();
        $xmlUrlSet = $xml->createElement("urlset");
        $xmlUrlSet->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
        array_push($filelist, compress_gz($filelocation, 'sitemap.xml'));

        $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl.'sitemap.xml.gz', 'priority' => 1, 'changefreq' => 'weekly'));
        $xmlUrlSet->appendChild( $xmlUrl );  
        array_push($filelist,compress_gz($filelocation, 'sitemap-vendor.xml'));
        $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl.'sitemap-vendor.xml.gz', 'priority' => 1, 'changefreq' => 'weekly'));
        $xmlUrlSet->appendChild( $xmlUrl );  
        
        
        foreach($mainCategories as $category){
            $categoryFileName = strtolower(url_clean($category['name']));
            array_push($filelist,compress_gz($filelocation, 'sitemap-'.$categoryFileName.'.xml'));
            $xmlUrl = write_url_xml($xml, array('loc' => $baseUrl.'sitemap-'.$categoryFileName.'.xml.gz', 'priority' => 1, 'changefreq' => 'weekly'));
            $xmlUrlSet->appendChild( $xmlUrl );  
        }
        
        $xml->appendChild( $xmlUrlSet );
        $xml->save($filelocation.'sitemap_index.xml');
        array_push($filelist,'sitemap_index.xml');
        
        $content = '';
        foreach($filelist as $file){
            $content = $content.$file.PHP_EOL;
        }
        
        $fp = fopen($filelocation. "filelist.txt", "wb");
        fwrite($fp,$content);
        fclose($fp);

        echo 'Sitemap generation complete. The following files have been generated:';
        foreach($filelist as $file){
            echo '<br/> '.$file;
        }
        echo '<br/> filelist.txt';
        $dbConnection = null;
       
         
    /**
     * Return the XML sitemap format based on http://www.sitemaps.org/protocol.html
     *
     * @param DOMDocument $xml
     * @param mixed DOMDocument
     * @return DOMElement
     *
     */
    function write_url_xml($xml, $data)
    {
        $xmlUrl = $xml->createElement("url");
        $xmlLoc = $xml->createElement("loc", htmlentities($data['loc']));
        $xmlPriority = $xml->createElement("priority", $data['priority']);
        $xmlLastmod = $xml->createElement("lastmod", date('Y-m-d'));
        $xmlChangefreq = $xml->createElement("changefreq", $data['changefreq']);
        $xmlUrl->appendChild( $xmlLoc );
        $xmlUrl->appendChild( $xmlPriority );
        $xmlUrl->appendChild($xmlLastmod);
        $xmlUrl->appendChild($xmlChangefreq);
        return $xmlUrl;
    }
    
    /**
     * Compresses a file using gzip 
     * 
     * @param string $filelocation
     * @param  string $filename
     * @return string
     *
     */
    function compress_gz($filelocation, $filename)
    {
        $gzfile = $filelocation.$filename.".gz";
        $fp = gzopen ($gzfile, 'w9');
        gzwrite ($fp, file_get_contents($filelocation.$filename));
        gzclose($fp);
        if (is_file($filelocation.$filename)){
            unlink($filelocation.$filename);
        }
        return $filename.".gz";
        
    }
    
    /**
     * Strips unwanted characters from a tring
     * 
     * @param string $string
     * @return string
     */
    function url_clean($string)
    {
        $string = preg_replace("/\s+/", " ", $string);
        $string = str_replace('-', ' ', trim($string)); 
        $string = preg_replace("/\s+/", " ", $string);
        $string = str_replace(' ', '-', trim($string));  
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  

        $string = str_replace('-', ' ', $string); 
        $string = str_replace(' ', '-', $string); 
        $string = str_replace('--', '-', $string);  
        return preg_replace('/\s+/','-', $string);
    }


