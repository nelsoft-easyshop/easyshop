<?php     
         $base_url= "https://easyshop.ph/";
         #Change filelocation as needed, e.g.: $filelocation = "C:/Users/Administrator/Dropbox/sitemap/";
         $filelocation = dirname(__FILE__).'/../../web/';
        /*
        $DBServer = '127.0.0.1';
        $DBUser   = 'easyshop';
        $DBPass   = 'MYSQL345y5h0p';
        $DBName   = 'easyshop';
        */
        
          $DBServer = '127.0.0.1';
        $DBUser   = 'root';
        $DBPass   = '121586';
        $DBName   = 'easyshop';
        $conn = new mysqli($DBServer, $DBUser, $DBPass, $DBName);
        if ($conn->connect_error) {
            exit('Database connection failed: '  . $conn->connect_error);
        }
                
         $xml = new DOMDocument();
         $xml_urlset = $xml->createElement("urlset");
         $xml_urlset->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );     
         $xml_url = write_url_xml($xml, array('loc' => $base_url, 'priority' => 1, 'changefreq' => 'never'));
         $xml_urlset->appendChild( $xml_url ); 
         $xml_url = write_url_xml($xml, array('loc' => $base_url.'sell/step1', 'priority' => 0.5, 'changefreq' => 'never'));
         $xml_urlset->appendChild( $xml_url );
         $xml_url = write_url_xml($xml, array('loc' => $base_url.'cart', 'priority' => 0.5, 'changefreq' => 'never'));
         $xml_urlset->appendChild( $xml_url );
         $xml_url = write_url_xml($xml, array('loc' => $base_url.'login', 'priority' => 0.5, 'changefreq' => 'never'));
         $xml_urlset->appendChild( $xml_url );
         $xml_url = write_url_xml($xml, array('loc' => $base_url.'register', 'priority' => 0.5, 'changefreq' => 'never'));
         $xml_urlset->appendChild( $xml_url );
         
         
         $xml->appendChild( $xml_urlset );  
         $xml->save($filelocation.'sitemap.xml');

   
        $sql="SELECT name, id_cat FROM es_cat WHERE parent_id = 1 AND id_cat != 1 AND is_main = 1";
        $rs=$conn->query($sql);
        if($rs === false) {
            exit('SQL Query Error: '  . $conn->error);
        } else {
            $rows = $rs->fetch_all(MYSQLI_ASSOC);
        }
        $main_categories = array();
         
        foreach($rows as $row){
  
            $xml = new DOMDocument();
            $xml_urlset = $xml->createElement("urlset");
            $xml_urlset->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
            
            $sql="SELECT CASE 
                            WHEN `GetFamilyTree` (id_cat) = '' THEN '0,0' 
                            ELSE `GetFamilyTree` (id_cat) 
                        END as `catlist`
                  FROM `es_cat` WHERE id_cat != 1 AND id_cat = ".$row['id_cat'];
            $rs=$conn->query($sql);
            if($rs === false) {
                exit('SQL Query Error: '  . $conn->error);
            } else {
                $cat_list= $rs->fetch_all(MYSQLI_ASSOC)[0]['catlist'];
            }
          
            $sql = "SELECT CONCAT('category/',slug) as url FROM es_cat WHERE id_cat IN (".$cat_list.")";
            $rs=$conn->query($sql);
            if($rs === false) {
                exit('SQL Query Error: '  . $conn->error);
            } else {
                $cats = $rs->fetch_all(MYSQLI_ASSOC);
            }

            
            foreach($cats as $x){
                $xml_url = write_url_xml($xml, array('loc' => $base_url.$x['url'], 'priority' => 0.5, 'changefreq' => 'monthly'));
                $xml_urlset->appendChild( $xml_url ); 
            }
 
            $sql="SELECT CONCAT('item/',slug) as url FROM es_product WHERE cat_id IN (".$cat_list.")";
            $rs=$conn->query($sql);
            if($rs === false) {
                exit('SQL Query Error: '  . $conn->error);
            } else {
                $products = $rs->fetch_all(MYSQLI_ASSOC);
            }
            foreach($products as $x){
                $xml_url = write_url_xml($xml, array('loc' => $base_url.$x['url'], 'priority' => 0.5, 'changefreq' => 'weekly'));
                $xml_urlset->appendChild( $xml_url );  
            }

             $xml->appendChild( $xml_urlset );  
             $row['name'] = url_clean($row['name']);
             $xml->save($filelocation.'sitemap-'.strtolower($row['name']).'.xml');
             array_push($main_categories, strtolower($row['name']));
             
         }
         #GENERATE VENDORS XML
         $xml = new DOMDocument();
         $xml_urlset = $xml->createElement("urlset");
         $xml_urlset->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
        
         
         
        $sql="SELECT CONCAT('vendor/',username) as url FROM es_member";
        $rs=$conn->query($sql);
        if($rs === false) {
            exit('SQL Query Error: '  . $conn->error);
        } else {
            $vendors = $rs->fetch_all(MYSQLI_ASSOC);
        }
        foreach($vendors as $x){
            $xml_url = write_url_xml($xml, array('loc' => $base_url.$x['url'], 'priority' => 0.5, 'changefreq' => 'monthly'));
            $xml_urlset->appendChild( $xml_url ); 
        }
         
         
         $xml->appendChild( $xml_urlset );
         $xml->save($filelocation.'sitemap-vendor.xml');
         
         $filelist = array();
         
         #GENERATE SITEMAP INDEX AND COMPRESS SITEMAPS
         $xml = new DOMDocument();
         $xml_urlset = $xml->createElement("urlset");
         $xml_urlset->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
         array_push($filelist, compress_gz($filelocation, 'sitemap.xml'));

         $xml_url = write_url_xml($xml, array('loc' => $base_url.'sitemap.xml.gz', 'priority' => 1, 'changefreq' => 'weekly'));
         $xml_urlset->appendChild( $xml_url );  
         array_push($filelist,compress_gz($filelocation, 'sitemap-vendor.xml'));
         $xml_url = write_url_xml($xml, array('loc' => $base_url.'sitemap-vendor.xml.gz', 'priority' => 1, 'changefreq' => 'weekly'));
         $xml_urlset->appendChild( $xml_url );  
         foreach($main_categories as $cat){
            array_push($filelist,compress_gz($filelocation, 'sitemap-'.$cat.'.xml'));
            $xml_url = write_url_xml($xml, array('loc' => $base_url.'sitemap-'.$cat.'.xml.gz', 'priority' => 1, 'changefreq' => 'weekly'));
            $xml_urlset->appendChild( $xml_url );  
         }
         
         $xml->appendChild( $xml_urlset );
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
            echo '<br/>'.$file;
         }
         echo '<br/>filelist.txt';
         
       
         
           
    function write_url_xml($xml, $data){
        $xml_url = $xml->createElement("url");
        $xml_loc = $xml->createElement("loc", htmlentities($data['loc']));
        $xml_priority = $xml->createElement("priority", $data['priority']);
        $xml_lastmod = $xml->createElement("lastmod", date('Y-m-d'));
        $xml_changefreq = $xml->createElement("changefreq", $data['changefreq']);
        $xml_url->appendChild( $xml_loc );
        $xml_url->appendChild( $xml_priority );
        $xml_url->appendChild($xml_lastmod);
        $xml_url->appendChild($xml_changefreq);
        return $xml_url;
    }
    
    function compress_gz($filelocation, $filename){
        // Name of the gz file we are creating
        $gzfile = $filelocation.$filename.".gz";
        // Open the gz file (w9 is the highest compression)
        $fp = gzopen ($gzfile, 'w9');
        // Compress the file
        gzwrite ($fp, file_get_contents($filelocation.$filename));
        // Close the gz file and we are done
        gzclose($fp);
        // Delete original file
        if (is_file($filelocation.$filename)){
            unlink($filelocation.$filename);
        }
        return $filename.".gz";
        
    }
    
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

?>