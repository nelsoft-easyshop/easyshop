<?php

    /**
     * Generates sphinx config
     *
     * @author sam gavinio <samgavinio@easyshop.ph>
     */
    include_once  __DIR__.'/bootstrap.php';
    $CI =& get_instance();
    
    $hostname = $CI->db->hostname;
    $sphinxDirectory =  __DIR__.'/../../sphinx';

    $hostArray = explode(';' , $CI->db->hostname);
    $hostname = null;
    foreach($hostArray as $hostField){
        $keyLocation = strpos($hostField, 'host=');
        if($keyLocation !== -1){
            $hostname = substr($hostField, $keyLocation + strlen('host='));
            break;
        }
    }
    
    if($hostname === null){
        echo 'Error: hostname cannot be found';
    }

    $configString = '
    
    source products { 
            type = mysql
            sql_host = '.$hostname.'
            sql_user = '.$CI->db->username.'
            sql_pass = '.$CI->db->password.'
            sql_db = '.$CI->db->database.'

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
        sql_host = '.$hostname.'
        sql_user = '.$CI->db->username.'
        sql_pass = '.$CI->db->password.'
        sql_db = '.$CI->db->database.'

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

    index products {

        source = products

        path = '.$sphinxDirectory.'/data/products

        wordforms = '.$sphinxDirectory.'/etc/wordforms.txt 

        min_word_len = 3

        min_infix_len = 3
    }

    index suggestions {

        source = suggestions

        path = '.$sphinxDirectory.'/data/suggestions

        morphology = metaphone
        
        min_word_len = 3

        min_infix_len = 2
    }

    searchd {
    
        max_filter_values = 16384

        log = '.$sphinxDirectory.'/logs/searchd.log
        query_log = '.$sphinxDirectory.'/logs/query.log
        pid_file = '.$sphinxDirectory.'/logs/searchd.pid

        listen = localhost:9312

    }';
    
    $file = $sphinxDirectory.'/etc/sphinx.conf';
    file_put_contents($file, $configString);
    
    echo 'sphinx.conf has been generated in /sphinx/etc/sphinx.conf';
