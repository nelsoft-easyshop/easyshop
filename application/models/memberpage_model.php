<?php


/**
 *  Memberpage controller
 *
 *  @author Sam Gavinio
 *  @author Stephen Janz Serafico
 *  @author Rain Jorque
 *
 */
class memberpage_model extends CI_Model
{
    /**
     *  Class Constructor
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->library("xmlmap");
        $this->load->library("parser");
        $this->config->load("image_path");
        $this->load->helper('product');
    }
    
    /**
     *  Fetches city, state/region, and city from database
     *
     *  @return $data with categorized locations
     */
    public function getLocationLookup()
    {
        $query = $this->xmlmap->getFilenameID('sql/users', 'getLocationLookup');
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        
        foreach($row as $r){
            if($r['type'] == 0){
                //CODE FOR MULTIPLE COUNTRY OPTIONS
                //$data['country_lookup'][$r['id_location']] = $r['location'];
                
                $data['country_name'] = $r['location'];
                $data['country_id'] = $r['id_location'];
            }
            else if($r['type'] == 3){
                $data['stateregion_lookup'][$r['id_location']] = $r['location'];
            }
            else if($r['type'] == 4){
                $data['city_lookup'][$r['parent_id']][$r['id_location']] = $r['location'];
            }
        }
        
        $data['json_city'] = json_encode($data['city_lookup'], JSON_FORCE_OBJECT);
        
        return $data;
    }
    
    
    /**
     *  Fetch user details
     *
     *  @return $row - contains all user info
     */
    public function get_member_by_id($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/users', 'get_member');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member', $member_id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }
    
    /**
     *  Fetch user's school information
     *
     *  @return $data - contains school data
     */
    public function get_school_by_id($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/users', 'get_school');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member', $member_id);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);		
        $data['school']=array();
        $i=0;
        foreach($rows as $row)
            $data['school'][$i++] = $row;
        return $data;
    }
    
    /**
     *  Fetch user's work information
     *
     *  @return $data - contains work data
     */
    public function get_work_by_id($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/users', 'get_work');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member',$member_id);
        $sth->execute();
        $rows= $sth->fetchAll(PDO::FETCH_ASSOC);
        $data['work'] = array();
        $i=0;
        foreach($rows as $row)
            $data['work'][$i++] = $row;
        return $data;
    }
    
    /**
     *  Fetch user's address information. Type 0 or 1
     *
     *  @return $row - contains address details
     */
    public function getAddress($member_id,$type)
    {
        $query = $this->xmlmap->getFilenameID('sql/users', 'getAddress');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id);
        $sth->bindParam(':type', $type);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
    
    /**
     *  Used for editing member information
     */
    public function edit_member_by_id($member_id, $data=array())
    {
        if(strlen($data['birthday']) == 0)
            //$data['birthday']='0000-00-00';
            $data['birthday']='0001-01-01';
        $query = $this->xmlmap->getFilenameID('sql/users', 'edit_member');
        $sth = $this->db->conn_id->prepare($query);	
        $sth->bindParam(':fullname', $data['fullname']);
        $sth->bindParam(':nickname', $data['nickname']);
        $sth->bindParam(':gender', $data['gender']);
        $sth->bindParam(':birthday', $data['birthday']);
        $sth->bindParam(':contactno', $data['contactno']);
        $sth->bindParam(':email', $data['email']);
        $sth->bindParam(':id_member', $member_id);     
        $sth->bindParam(':is_contactno_verify', $data['is_contactno_verify']);
        $sth->bindParam(':is_email_verify', $data['is_email_verify']);
        $result = $sth->execute();
        
        return $result;
    }
    
    /**
     *  Function used to insert or edit address under Personal Information Tab
     */
    public function editAddress($member_id, $data=array(), $address_id)
    {
        if( (string)$address_id === '' ){
            $query = $this->xmlmap->getFilenameID('sql/users', 'insertAddress');
            $sth = $this->db->conn_id->prepare($query);
        }
        else{
            $query = $this->xmlmap->getFilenameID('sql/users', 'updateAddress');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id_address', $address_id);
        }
        
        $sth->bindParam(':type', $data['addresstype']);
        $sth->bindParam(':id_member', $member_id);
        $sth->bindParam(':stateregion', $data['stateregion']);
        $sth->bindParam(':city', $data['city']);
        $sth->bindParam(':address', $data['address']);
        $sth->bindParam(':country', $data['country']);
        $sth->bindparam(':consignee', $data['consignee']);
        $sth->bindparam(':mobile', $data['mobile']);
        $sth->bindparam(':telephone', $data['telephone']);
        
        $sth->bindParam(':lat', $data['lat']);
        $sth->bindParam(':lng', $data['lng']);
        
        $result = $sth->execute();

        return $result;
    }
    
    /**
     *  Function used to edit school information
     */
    public function edit_school_by_id($member_id, $data=array())
    {
        if(strlen($data['year'])==0){
            $data['year'] = '0000';
        }
        else{
            $data['year'] = ($data['year'] < 1901)?1901:$data['year'];
            $data['year']= ($data['year'] > 2155)?2155:$data['year'];
        }
        $query = $this->xmlmap->getFilenameID('sql/users', 'edit_school');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':schoolname', $data['school']);
        $sth->bindParam(':year', $data['year']);
        $sth->bindParam(':level', $data['level']);
        $sth->bindParam(':school_count', $data['school_count']);
        $sth->bindParam(':id_member', $member_id);
        
        $result = $sth->execute();

        return $result;
    }

    /**
     *  Function used to delete address, school, and work information
     */
    public function deletePersonalInformation($member_id, $field)
    {
        if($field === 'del_address' ){
            $query = $this->xmlmap->getFilenameID('sql/users', 'delete_address');
        }
        if($field === 'del_school' ){
            $query = $this->xmlmap->getFilenameID('sql/users', 'delete_school');
        }
        if($field === 'del_work' ){
            $query = $this->xmlmap->getFilenameID('sql/users', 'delete_work');
        }
        
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $member_id);
        $result = $sth->execute();
        
        return $result;
    }
    
    /**
     *  Used for uploading banner in vendor page
     *  Includes resizing of image and processing of cropped image
     */
    public function banner_upload($uid, $data=array()){
        $query = $this->xmlmap->getFilenameID('sql/users', 'get_image');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $uid);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $path = $row['imgurl'];	
        
        if(trim($path) === ''){
            $path = $this->config->item('user_img_directory').$path.$row['id_member'].'_'.$row['username'];
        }
        if(!is_dir($path)){
          mkdir($path,0755,TRUE); 
        }
        $config['overwrite'] = TRUE;
        $config['file_name'] = 'banner.png';
        $config['upload_path'] = $path; 
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']	= '5000';
        $config['max_width']  = '5000';
        $config['max_height']  = '5000';
        $this->upload->initialize($config);
        
        if ( ! $this->upload->do_upload()){
            return array('error' => $this->upload->display_errors());
        }
        else{
            $config['image_library'] = 'gd2';
            $config['source_image'] = $path.'/banner.png';
            $config['maintain_ratio'] = false;
            
            $imageData = $this->upload->data();
            
            // If cropped
            if($data['w'] > 0 && $data['h'] > 0){
                $config['new_image'] = $path.'/banner.png';
                $config['width'] = $data['w'];
                $config['height'] = $data['h'];
                $config['x_axis'] = $data['x'];
                $config['y_axis'] = $data['y'];
                $this->image_lib->initialize($config);  
                $this->image_lib->image_process_gd('crop');
                $config['x_axis'] = $config['y_axis'] = '';
            }
            
            //Resize to standard banner size
            $config['new_image'] = $path.'/banner.png';
            $config['width'] = 1475;
            $config['height'] = 366;
            $this->image_lib->initialize($config);  
            $this->image_lib->resize();	

            $isHide = 0;
            $query = $this->xmlmap->getFilenameID('sql/users', 'update_imgurl_banner');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':path', $path);
            $sth->bindParam(':id_member', $uid);
            $sth->bindParam(':is_hide_banner', $isHide);
            $sth->execute();
        }
    }
    
    /**
     *  Used for editing work info in Personal Information tab
     *
     *  @return TRUE on success, False otherwise
     */
    public function edit_work_by_id($member_id, $data=array())
    {
        $rowcount = count($data) / 4;
        
        if(strlen($data['year'])==0){
            $data['year'] = '0000';
        }
        else{
            $data['year'] = ($data['year'] < 1901)?1901:$data['year'];
            $data['year']= ($data['year'] > 2155)?2155:$data['year'];
        }
        
        $query = $this->xmlmap->getFilenameID('sql/users', 'edit_work');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindparam(':id_member', $member_id);
        $sth->bindparam(':companyname', $data['companyname']);
        $sth->bindparam(':designation', $data['designation']);
        $sth->bindparam(':year', $data['year']);
        $sth->bindparam(':count', $data['count']);
        $result = $sth->execute();
        
        return $result;
    }
    
    /**
     *  Function used to fetch user avatar and banner images
     *  Vendor : fetches banner image (returns URL)
     *  Feedback : fetches avatar in 60x60 png for feedback display in vendor page (returns URL)
     *  Default : fetches user avatar for memberpage and vendorpage (returns img tag)
     */
    public function get_image($member_id, $selector=""){		
        $path = $this->config->item('user_img_directory');
        $query = $this->xmlmap->getFilenameID('sql/users', 'get_image');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $member_id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        switch($selector){
            case "vendor":
                $img_file = $row['imgurl'].'/banner.png';
                if(!file_exists($img_file)||(trim($row['imgurl']) === '')){
                    $user_image = base_url().$path.'default/banner.png?'.time();
                }
                else{
                    $user_image = base_url().$img_file.'?'.time();
                }
                break;
            case "feedback":
                $img_file = $row['imgurl'].'/60x60.png';
                if(!file_exists($img_file)||(trim($row['imgurl']) === '')){
                    $user_image = base_url().$path.'default/60x60.png?'.time();
                }
                else{
                    $user_image = base_url().$img_file.'?'.time();
                }
                break;
            default:
                $img_file = $row['imgurl'].'/150x150.png';
                if(!file_exists($img_file)||(trim($row['imgurl']) === '')){
                    $user_image = img(array('src' => $path.'default/150x150.png?'.time(), 'id' => 'user_image'));
                }
                else{
                    $user_image = img(array('src' => $img_file.'?'.time(), 'id' => 'user_image'));
                }
                break;
        }
        return $user_image;
    }
    
    /**
     *  Used by form validation to check if SELECT has value provided
     *
     *  @return TRUE on success, FALSE otherwise
     */
    public function select_set($val, $arg=array())
    {
        if($val !== $arg[0])
            return TRUE;
        $this->form_validation->set_message('external_callbacks', 'This field must be set');
        return FALSE;
    }
    
    /**
     *  Used to check if date provided is valid
     *
     *  @return TRUE on success, FALSE otherwise
     */
    public function is_validdate($date)
    {
        if(trim($date) === '')
            return true;
    
        $comp = preg_split('/[-\/]+/', $date);
        $year = intval($comp[0]);
        $month = intval($comp[1]);
        $day = intval($comp[2]);

        if(checkdate($month, $day, $year)) {
            return true;
        }
        else{
            $this->form_validation->set_message('external_callbacks', 'The date you entered is invalid');
            return false;
        }
    }
    
    /**
     *  Check query and adjust filter for order_product_status 
     *  Current filter : 2 = returned , 6 = dragonpay expired  
     *  @return 'active', 'deleted' and 'sold' count
     */
    public function getUserItemCount($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getUserItemCount');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }
    
    /**
     *  Fetch product count with filter provided
     */
    public function getUserItemSearchCount($member_id, $schVal,$deleteStatus, $draftStatus)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getUserItemSearchCount');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id, PDO::PARAM_INT);
        $sth->bindParam(':schval', $schVal, PDO::PARAM_STR);
        $sth->bindParam(':delete_status',$deleteStatus, PDO::PARAM_INT);
        $sth->bindParam(':draft_status',$draftStatus, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        $count = (int)$row['product_count'];
        
        return $count;
    }
    
    /**
     *  Function used to fetch user items in dashboard.
     *  Includes search, filter, and order by functionality.
     *  
     *  @return $data - contains product details for parsing of html template
     */
    public function getUserItems($member_id, $deleteStatus, $draftStatus=0, $start=0, $nf='%', $of="p.lastmodifieddate" , $osf="DESC" , $itemPerPage=10)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getUserItems');
        $parseData = array(
          'order_filter' => $of,
          'order_sequence_filter' => $osf
        );
        $query = $this->parser->parse_string($query,$parseData,true);
        
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$member_id, PDO::PARAM_INT);
        $sth->bindParam(':delete_status',$deleteStatus, PDO::PARAM_INT);
        $sth->bindParam(':draft_status',$draftStatus, PDO::PARAM_INT);
        $sth->bindParam(':start',$start, PDO::PARAM_INT);
        $sth->bindParam(':number',$itemPerPage, PDO::PARAM_INT);
        $sth->bindParam(':name_filter',$nf, PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        
        foreach($rows as $key=>$row){
        
            applyPriceDiscount($row);
        
            if( strlen(trim($row['name'])) === 0 ){
                $row['name'] = "Untitled";
            }

            $query = $this->xmlmap->getFilenameID('sql/product','getParent');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$row['cat_id']);
            $sth->execute();
            $parents = $sth->fetchAll(PDO::FETCH_ASSOC);
            $row['parents'] = array();
            foreach($parents as $parent){
                array_push($row['parents'], $parent['name']);
            }
            if( strlen(trim($row['cat_other_name'])) > 0 ){
                array_push($row['parents'], $row['cat_other_name']);
            }
            
            $query = $this->xmlmap->getFilenameID('sql/product','getProductAttributes');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$row['id_product']);
            $sth->execute();
            $attributes = $sth->fetchAll(PDO::FETCH_ASSOC);

            $data_attr = array();
            foreach($attributes as $attribute){
                if( (int)$attribute['datatype_id'] !== 2){
                    $index = $attribute['name'];
                    if(!array_key_exists($index, $data_attr))
                        $data_attr[$index] = array();
                    array_push($data_attr[$index],array('value' => $attribute['attr_value'], 'price'=>$attribute['attr_price']));
                }
            }
            $row['data_attr'] = $data_attr;
            
            if(trim($row['product_image_path']) === ''){
                $row['path'] = 'assets/product/default/';
                $row['file'] = 'default_product_img.jpg';
            }
            else{
                $row['product_image_path'] = ($row['product_image_path'][0]=='.')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
                $row['product_image_path'] = ($row['product_image_path'][0]=='/')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
                $rev_url = strrev($row['product_image_path']);
                $row['path'] = substr($row['product_image_path'],0,strlen($rev_url)-strpos($rev_url,'/'));
                $row['file'] = substr($row['product_image_path'],strlen($rev_url)-strpos($rev_url,'/'),strlen($rev_url));
            }
            unset($row['product_image_path']);
            $data[] = $row;
        }

        return $data;
    }
    
    /**
     *  Fetch vendor details (member details) using slug
     */
    public function getVendorDetails($sellerslug)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getVendorDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':userslug',$sellerslug);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }
    
    /**
     *  Fetch Transactions to be categorized under Buy (on-going and complete)
     *  Status = 0 for ongoing (default), 1 for complete
     *
     *  @return $data - contains transaction details for parsing html template
     */
    public function getBuyTransactionDetails($contentXml, $member_id, $status = 0, $start = 0, $nf='%', $of="1,2,3,5" , $osf="DESC" , $itemPerPage=10)
    {    
        $query = $this->xmlmap->getFilenameID('sql/users','getBuyTransactionDetails');
        $strStatus = $status === 1 ? '1' : '0,99';
        $parseData = array(
            'order_status' => $strStatus,
            'payment_filter' => $of,
            'order_sequence_filter' => $osf,
            'limit' => 'LIMIT :start, :number'
        );
        $query = $this->parser->parse_string($query,$parseData,true);
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $member_id, PDO::PARAM_INT);
        $sth->bindParam(':start', $start, PDO::PARAM_INT);
        $sth->bindParam(':number', $itemPerPage, PDO::PARAM_INT);
        $sth->bindParam(':name_filter', $nf, PDO::PARAM_STR);
        
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        
        foreach( $row as $r){
            // Assemble Outer data array
            if( !isset($data[$r['id_order']]) ){
                $data[$r['id_order']] = array_slice($r,1,4);
                $data[$r['id_order']]['is_flag'] = $r['is_flag'];
                $data[$r['id_order']] = array_merge( $data[$r['id_order']], array('users'=>array(),'products'=>array()) );
            }

            #Fetch order product details
            $query = $this->xmlmap->getFilenameID('sql/users','getOrderProductTransactionDetails');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':order_id', $r['id_order'], PDO::PARAM_INT);
            $sth->execute();
            $op = $sth->fetchAll(PDO::FETCH_ASSOC);

            foreach($op as $p){
                //Assemble User Array
                if( !isset($data[$r['id_order']]['users'][$p['seller_id']]) ){
                    $data[$r['id_order']]['users'][$p['seller_id']] = array(
                        'name' => $p['seller'],
                        'slug' => $p['sellerslug'],
                        'has_feedb' => (int)$p['for_memberid'] === 0 ? 0 : 1
                    );
                }
                //Assemble Product Array
                if( !isset($data[$r['id_order']]['products'][$p['id_order_product']]) ){
                    $product = array_slice($p,1,13);
                    $product['has_shipping_summary'] = 0;
                    $product['seller_id'] = $p['seller_id'];
                    $product['seller'] = $p['seller'];
                    $product['seller_slug'] = $p['sellerslug'];
                    if( trim(strlen($p['courier']))>0 && trim(strlen($p['datemodified']))>0 ){
                        $product['has_shipping_summary'] = 1;
                    }
                    $data[$r['id_order']]['products'][$p['id_order_product']] = $product;
                    $imagepath[0] = array(
                        'product_image_path' => $p['product_image_path']
                    );
                    explodeImagePath($imagepath);
                    $data[$r['id_order']]['products'][$p['id_order_product']]['product_image_path'] = $imagepath[0]['path'] . 'thumbnail/' . $imagepath[0]['file'];
                }

                //Assemble product attribute array
                if(!isset($data[$r['id_order']]['products'][$p['id_order_product']]['attr'])){
                    $data[$r['id_order']]['products'][$p['id_order_product']]['attr'] = array();
                }
                if( (string)$p['attr_name']!=='' && (string)$p['attr_value']!=='' ){
                    array_push($data[$r['id_order']]['products'][$p['id_order_product']]['attr'], array('field' => ucwords(strtolower($p['attr_name'])), 'value' => ucwords(strtolower($p['attr_value'])) ));
                }
            }
            //IF BANK DEPOSIT
            if( (int)$r['payment_method'] === 5 && (int)$r['transac_stat'] === 99 ){
                $query = $this->xmlmap->getFilenameID('sql/users','getTransactionBankDepositDetails');
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':order_id', $r['id_order']);
                $sth->execute();
                $pbd = $sth->fetch(PDO::FETCH_ASSOC);
                $data[$r['id_order']]['bd_details'] = $pbd;
                if(!isset($data['bank_template'])){
                    $data[$r['id_order']]['bank_template']['bank_name'] = $this->xmlmap->getFilenameID($contentXml,'bank-name');
                    $data[$r['id_order']]['bank_template']['bank_accname'] = $this->xmlmap->getFilenameID($contentXml,'bank-account-name');
                    $data[$r['id_order']]['bank_template']['bank_accnum'] = $this->xmlmap->getFilenameID($contentXml,'bank-account-number');
                }
                
            }
        }
        return $data;
    }
    
    /**
     *  Fetch Transactions to be categorized under Sell (on-going and complete)
     *  Status = 0 for ongoing (default), 1 for complete
     *
     *  @return $data - contains transaction details for parsing html template
     */
    public function getSellTransactionDetails($member_id,$status,$start=0, $nf='%', $of="1,2,3,5" , $osf="DESC" , $itemPerPage=10)
    {
        #Fetch order details
        $query = $this->xmlmap->getFilenameID('sql/users','getSellTransactionDetails');
        $total = 'COUNT(op.status)';
        $responsed = 'SUM( CASE WHEN op.status != 0 THEN 1 ELSE 0 END )';
        $strStatus = $status === 1 ? $total.'='.$responsed : $total.'>'.$responsed;
        $parseData = array(
            'response_stat' => $strStatus,
            'payment_filter' => $of,
            'order_sequence_filter' => $osf,
            'limit' => 'LIMIT :start, :number'
        );
        $query = $this->parser->parse_string($query,$parseData,true);
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $member_id, PDO::PARAM_INT);
        $sth->bindParam(':start', $start, PDO::PARAM_INT);
        $sth->bindParam(':number', $itemPerPage, PDO::PARAM_INT);
        $sth->bindParam(':name_filter', $nf, PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        
        foreach( $row as $r ){
            // Assemble Outer data array
            if( !isset($data[$r['id_order']]) ){
                $data[$r['id_order']] = array_slice($r,1,6);
                $data[$r['id_order']]['is_flag'] = $r['is_flag'];
                $data[$r['id_order']]['buyer_slug'] = $r['buyerslug'];
                $data[$r['id_order']] = array_merge( $data[$r['id_order']], array('users'=>array(),'products'=>array()) );
            }
            
            //Assemble User Array
            if( !isset($data[$r['id_order']]['users'][$r['buyer_id']]) ){
                $data[$r['id_order']]['users'][$r['buyer_id']] = array(
                    'name' => $r['buyer'],
                    'slug' => $r['buyerslug'],
                    'has_feedb' => (int)$r['for_memberid'] === 0 ? 0 : 1,
                    'address' => array_slice($r,7,8)
                );
            }
            
            #Fetch order product details
            $query = $this->xmlmap->getFilenameID('sql/users','getOrderProductTransactionDetails');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':order_id', $r['id_order'], PDO::PARAM_INT);
            $sth->execute();
            $op = $sth->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($op as $p){
                if( (int)$member_id !== (int)$p['seller_id'] ){
                    continue;
                }
            
                //Assemble Product Array
                if( !isset($data[$r['id_order']]['products'][$p['id_order_product']]) ){
                    $product = array_slice($p,1,13);
                    $product['has_shipping_summary'] = 0;
                    if( trim(strlen($p['courier']))>0 && trim(strlen($p['datemodified']))>0 ){
                        $product['has_shipping_summary'] = 1;
                    }
                    $data[$r['id_order']]['products'][$p['id_order_product']] = $product;
                    $imagepath[0] = array(
                        'product_image_path' => $p['product_image_path']
                    );
                    explodeImagePath($imagepath);
                    $data[$r['id_order']]['products'][$p['id_order_product']]['product_image_path'] = $imagepath[0]['path'] . 'thumbnail/' . $imagepath[0]['file'];
                }
                
                //Assemble product attribute array
                if(!isset($data[$r['id_order']]['products'][$p['id_order_product']]['attr'])){
                    $data[$r['id_order']]['products'][$p['id_order_product']]['attr'] = array();
                }
                if( (string)$p['attr_name']!=='' && (string)$p['attr_value']!=='' ){
                    array_push($data[$r['id_order']]['products'][$p['id_order_product']]['attr'], array('field' => ucwords(strtolower($p['attr_name'])), 'value' => ucwords(strtolower($p['attr_value'])) ));
                }
            }
        }
        
        return $data;
    }
    
    /**
     *  Fetch # of transactions satisfying filter. Used to update pagination
     *
     *  @return count($row) - quantity of products based on filter
     */
    public function getFilteredTransactionCount($member_id, $status, $nf='%', $of="1,2,3,5" , $osf="DESC" , $querySelect)
    {
        switch($querySelect){
            case 'buy':
                $query = $this->xmlmap->getFilenameID('sql/users','getBuyTransactionDetails');
                $strStatus = $status === 1 ? '1' : '0,99';
                $parseData = array(
                    'order_status' => $strStatus,
                    'payment_filter' => $of,
                    'order_sequence_filter' => $osf,
                    'limit' => ''
                );
                $query = $this->parser->parse_string($query,$parseData,true);
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':id', $member_id, PDO::PARAM_INT);
                $sth->bindParam(':name_filter', $nf, PDO::PARAM_STR);
                
                $sth->execute();
                $row = $sth->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'sell':
                $query = $this->xmlmap->getFilenameID('sql/users','getSellTransactionDetails');
                $total = 'COUNT(op.status)';
                $responsed = 'SUM( CASE WHEN op.status != 0 THEN 1 ELSE 0 END )';
                $strStatus = $status === 1 ? $total.'='.$responsed : $total.'>'.$responsed;
                $parseData = array(
                    'response_stat' => $strStatus,
                    'payment_filter' => $of,
                    'order_sequence_filter' => $osf,
                    'limit' => ''
                );
                $query = $this->parser->parse_string($query,$parseData,true);
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':id', $member_id, PDO::PARAM_INT);
                $sth->bindParam(':name_filter', $nf, PDO::PARAM_STR);
                $sth->execute();
                $row = $sth->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
        
        return count($row);
    }
    
    /**
     *  Fetch initial count of all transaction categories
     */
    public function getTransactionCount($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getTransactionCount');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id', $member_id, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array(
            'buy' => array(),
            'sell' => array(),
            'cbuy' => array(),
            'csell' => array()
        );
        
        foreach( $row as $r ){
            if( intval($member_id) === intval($r['seller_id']) ){ #you are seller, consider your total number of response
                if( $r['total']===$r['responsed'] && !in_array($r['id_order'],$data['csell']) ){ #complete
                    $data['csell'][] = $r['id_order'];
                }
                else if( $r['total']>$r['responsed'] && !in_array($r['id_order'],$data['sell']) ){ #ongoing
                    $data['sell'][] = $r['id_order'];
                }
            }
            else{ #you are buyer (consider only order_status)
                if( intval($r['order_status']) !== 1 && !in_array($r['id_order'],$data['buy']) ){ #ongoing
                    $data['buy'][] = $r['id_order'];
                }
                else if( intval($r['order_status']) === 1 && !in_array($r['id_order'],$data['cbuy']) ){ #complete
                    $data['cbuy'][] = $r['id_order'];
                }
            }
        }
        
        $fdata = array(
            'buy' => count($data['buy']),
            'sell' => count($data['sell']),
            'cbuy' => count($data['cbuy']),
            'csell' => count($data['csell'])
        );
        
        return $fdata;
    }
    
    /**
     *  Function used to authenticate user when sending response in transactions tab
     *
     *  @return TRUE when authenticated, FALSE otherwise
     */
    public function authenticateUser($data)
    {
        $query = $this->xmlmap->getFilenameID('sql/users', 'authenticateUser');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $data['username']);
        $sth->bindParam(':password', $data['password']);
        $sth->bindParam(':member_id', $data['member_id']);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $row['id_member'] ? true : false ;
    }
    
    /**
     *  Used to add feedback
     *
     *  @return TRUE on success, FALSE otherwise
     */
    public function addFeedback($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','addFeedback');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $temp['uid']);
        $sth->bindParam(':for_memberid', $temp['for_memberid']);
        $sth->bindParam(':feedb_msg', $temp['feedb_msg']);
        $sth->bindParam(':feedb_kind', $temp['feedb_kind']);
        $sth->bindParam(':order_id', $temp['order_id']);
        $sth->bindParam(':rating1', $temp['rating1']);
        $sth->bindParam(':rating2', $temp['rating2']);
        $sth->bindParam(':rating3', $temp['rating3']);
        $result = $sth->execute();
        
        return $result;
    }
    
    /**
     *  Used to check if feedback has already been provided depending on
     *      member, for member, and direction of feedback and order id
     *
     *  @return $row - to confirm if feedback has already been provided or not
     */
    public function checkFeedback( $temp )
    {
        $query = $this->xmlmap->getFilenameID('sql/users','checkFeedback');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $temp['uid']);
        $sth->bindParam(':for_memberid', $temp['for_memberid']);
        $sth->bindParam(':feedb_kind', $temp['feedb_kind']);
        $sth->bindParam(':order_id', $temp['order_id']);
        $result = $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /**
     *  Fetch feedbacks for user. (for user and posted by user)
     */
    public function getFeedback($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getFeedback');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$member_id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array(
            'youpost_buyer' => array(),
            'youpost_seller' => array(),
            'otherspost_seller' => array(),
            'otherspost_buyer' => array(),
            'afbcount' => 0,
            'rating1' => 0,
            'rating2' => 0,
            'rating3' => 0,
            'rcount' => 0
        );
        
        foreach($row as $k=>$result){
            if($result['member_id'] == $member_id){ //you post feedback
                unset($result['member_id']);
                unset($result['member_name']);
                unset($result['member_slug']);
                $temp = array_slice($result,0,8);
                $temp['user_image'] = $this->get_image($result['for_memberid'], 'feedback');
                if($result['feedb_kind'] == 0){ //you are buyer
                    $data['youpost_buyer'][$result['order_id']][] = $temp;
                }
                else if($result['feedb_kind'] == 1){ //you are seller
                    $data['youpost_seller'][$result['order_id']][] = $temp;
                }
            }
            else if($result['for_memberid'] == $member_id){ //others post feedback
                unset($result['for_memberid']);
                unset($result['for_membername']);
                unset($result['for_memberslug']);
                $temp = array_slice($result,0,8);
                $temp['user_image'] = $this->get_image($result['member_id'], 'feedback');
                $data['rating1'] += $result['rating1'];
                $data['rating2'] += $result['rating2'];
                $data['rating3'] += $result['rating3'];
                $data['rcount']++;
                if($result['feedb_kind'] == 0){ //you are seller
                    $data['otherspost_seller'][$result['order_id']][] = $temp;
                }
                else if($result['feedb_kind'] == 1){ //you are buyer
                    $data['otherspost_buyer'][$result['order_id']][] = $temp;
                }
            }
            $data['afbcount']++;
        }
        
        if($data['rcount'] !==0 ){
            $data['rating1'] = round($data['rating1'] / $data['rcount']);
            $data['rating2'] = round($data['rating2'] / $data['rcount']);
            $data['rating3'] = round($data['rating3'] / $data['rcount']);
        }

        return $data;
    }
    
    /**
     *  Function used to check for default, and add billing info
     *  Returns ID of billing info inserted
     */
    public function billing_info($data)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getDefaultBillingAccnt');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$data['member_id']);
        $sth->execute();

        $is_default = ($sth->rowCount() == 0)?'1':'0';

        $query = $this->xmlmap->getFilenameID('sql/users','addBillingAccnt');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $data['member_id']);
        $sth->bindParam(':payment_type', $data['payment_type']);
        $sth->bindParam(':user_account', $data['user_account']);
        $sth->bindParam(':bank_id', $data['bank_id']);
        $sth->bindParam(':bank_account_name', $data['bank_account_name']);
        $sth->bindParam(':bank_account_number', $data['bank_account_number']);
        $sth->bindParam(':is_default', $is_default);

        $sth->execute();
        
        $id =  $this->db->conn_id->lastInsertId('id_billing_info');
        return $id;
    }
    
    /**
     *  The update here is implemented by updating the current entry and inserting a copy
     *  of the previous entry for history purposes.
     */
    public function billing_info_update($data)
    {

        //GET PAYMENT ACCOUNT DETAIL
        $query =  $this->xmlmap->getFilenameID('sql/users','getBillingAccountById');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $data['member_id']);		
        $sth->bindParam(':ibi', $data['ibi']);
        $sth->execute();
        $billing_detail = $sth->fetch(PDO::FETCH_ASSOC);
        //UPDATE CURRENT BILLING DETAIL WITH NEW INFO
        $query = $this->xmlmap->getFilenameID('sql/users','updateBillingAccnt');
        $sth = $this->db->conn_id->prepare($query);	
        $sth->bindParam(':bank_id',  $data['bank_id']);
        $sth->bindParam(':bank_account_name', $data['bank_account_name']);
        $sth->bindParam(':bank_account_number', $data['bank_account_number']);
        $sth->bindParam(':payment_type', $data['payment_type']);
        $sth->bindParam(':user_account', $billing_detail['user_account']);
        $sth->bindParam(':is_default', $billing_detail['is_default']);
        $sth->bindParam(':is_delete', $billing_detail['is_delete']);
        $sth->bindParam(':member_id', $data['member_id']);	
        $sth->bindParam(':ibi', $data['ibi']);
        $sth->execute();
        
        //SAVE A COPY OF THE PREVIOUS ENTRY WITH IS_DELETE = 1
        $query = $this->xmlmap->getFilenameID('sql/users','InsertHistoryBillingAccnt');
        $sth = $this->db->conn_id->prepare($query);
        $billing_detail['is_default'] = 0;
        $billing_detail['is_delete'] = 1;
        $sth->bindParam(':member_id', $data['member_id']);
        $sth->bindParam(':payment_type', $billing_detail['payment_type']);
        $sth->bindParam(':user_account', $billing_detail['user_account']);
        $sth->bindParam(':bank_id', $billing_detail['bank_id']);
        $sth->bindParam(':bank_account_name', $billing_detail['bank_account_name']);
        $sth->bindParam(':bank_account_number', $billing_detail['bank_account_number']);
        $sth->bindParam(':is_default', $billing_detail['is_default']);
        $sth->bindParam(':is_delete', $billing_detail['is_delete'] );
        $sth->bindParam(':date_added', $billing_detail['dateadded']);
        $sth->execute();
    }
    
    /**
     *  Function used to set default billing info of user
     */
    public function billing_info_default($data)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','clearDefaultBillingAccnt');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $data['member_id']);
        $sth->execute();

        $query = $this->xmlmap->getFilenameID('sql/users','setDefaultBillingAccnt');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $data['member_id']);
        $sth->bindParam(':ibi', $data['ibi']);
        $sth->execute();
    }
    
    /**
     *  Used to delete billing info
     */
    public function billing_info_delete($data)
    {
        $query = "UPDATE `es_billing_info` SET `is_delete` = 1, `datemodified` = NOW() WHERE `member_id`=:member_id AND `id_billing_info`=:ibi";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $data['member_id']);		
        $sth->bindParam(':ibi', $data['ibi']);
        $sth->execute();
        
        $query = "SELECT ebi.`id_billing_info` FROM `es_billing_info` ebi 
            WHERE ebi.`member_id`=:member_id AND `id_billing_info`=:ibi AND ebi.`is_default` = 1 LIMIT 1 ";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$data['member_id']);
        $sth->bindParam(':ibi', $data['ibi']);
        $sth->execute();

        if($sth->rowCount() > 0){
            $query = "SELECT ebi.`id_billing_info` FROM `es_billing_info` ebi 
            WHERE ebi.`member_id`=:member_id AND ebi.`is_delete` = 0 LIMIT 1 ";		
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':member_id',$data['member_id']);
            $sth->execute();
            $arr = $sth->fetchAll(PDO::FETCH_ASSOC);
    
            if($sth->rowCount() > 0){
                foreach($arr as $value){
                    $ibi = $value['id_billing_info'];
                }
                $query = "UPDATE `es_billing_info` SET `is_default` = 1, `datemodified` = NOW() WHERE `member_id`=:member_id AND `id_billing_info`=:ibi";
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':member_id',$data['member_id']);
                $sth->bindParam(':ibi', $ibi);
                $sth->execute();
            }
        }
    }
    
    /**
     *  Fetch billing info
     */
    public function get_billing_info($data)
    {
        $query = "SELECT
            ebi.`id_billing_info`, 
            ebi.`payment_type`,
            ebi.`user_account`,
            ebi.`bank_id`, 
            ebki.`bank_name`,
            ebi.`bank_account_name`,
            ebi.`bank_account_number`,
            ebi.`is_default`,
            ep.`slug`, ep.`name`, ep.`brief`, ep.`createddate`
        FROM `es_billing_info` ebi 
        LEFT JOIN `es_bank_info` ebki ON ebi.`bank_id` = ebki.`id_bank` 
        LEFT JOIN `es_product` ep ON ep.billing_info_id = ebi.`id_billing_info` 
        WHERE ebi.`member_id`=:member_id AND ebi.`is_delete` = 0 ORDER BY ebi.`is_default` DESC, ep.createddate DESC";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$data);
        $sth->execute();
        $rows= $sth->fetchAll(PDO::FETCH_ASSOC);
        $response = array();
        foreach($rows as $row){
            if(!isset($response[$row['id_billing_info']])){
                $response[$row['id_billing_info']]['id_billing_info'] = $row['id_billing_info'];
                $response[$row['id_billing_info']]['payment_type'] = $row['payment_type'];
                $response[$row['id_billing_info']]['user_account'] = $row['user_account'];
                $response[$row['id_billing_info']]['bank_id'] = $row['bank_id'];
                $response[$row['id_billing_info']]['bank_name'] = $row['bank_name'];
                $response[$row['id_billing_info']]['bank_account_name'] = $row['bank_account_name'];
                $response[$row['id_billing_info']]['bank_account_number'] = $row['bank_account_number'];
                $response[$row['id_billing_info']]['is_default'] = $row['is_default'];
                $response[$row['id_billing_info']]['products'] = array();
            }
            else{
                array_push($response[$row['id_billing_info']]['products'], array('p_slug' => $row['slug'], 'p_name' => $row['name'], 'p_briefdesc' => $row['brief'], 'p_date' => $row['createddate']));
            }
        }
        
        return $response;
    }
    
    /**
     *  Fetch bank list from database
     */
    public function getAllBanks()
    {
        $query = "SELECT * FROM es_bank_info";
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $rows= $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     *  Fetch bank specific
     */
    public function get_bank($bank, $toggle)
    {
        if($toggle == 'name'){
            $query = "SELECT `id_bank` AS 'id', `bank_name` AS 'name' FROM `es_bank_info` WHERE `bank_name` LIKE CONCAT(CONCAT('%',:bank),'%')";
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':bank', $bank, PDO::PARAM_STR);
        }
        else{
            $query = "SELECT `id_bank` AS 'id', `bank_name` AS 'name' FROM `es_bank_info`";
            $sth = $this->db->conn_id->prepare($query);
        }
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;							
    }
    
    /**
     *  Check if bank account is unique
     */
    public function isBankAccountUnique($data)
    {
        $query = "SELECT * FROM `es_billing_info` WHERE `member_id` = :member_id AND `bank_account_number` = :bank_account_number AND `is_delete` = 0";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$data['member_id']);
        $sth->bindParam(':bank_account_number',$data['bank_account_number']);	
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $data['ibi'] = isset($data['ibi'])?$data['ibi']:0;
        $response = false;
        if((!$row)||(intval($row['id_billing_info'],10) == intval($data['ibi'],10))){
            $response = true;
        } 
        return $response;
    }
    
    /**
     *  Fetch product using billing info data
     */
    public function getProductsByBillingInfo($billing_id)
    {
        $query = "SELECT id_product, name, brief FROM `es_product` WHERE `billing_info_id` = :billing_id AND `is_delete` = 0 AND `is_draft` = 0";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':billing_id',$billing_id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    
    /**
     *  Fetch next payout data under sales tab
     */
    public function getNextPayout($member_id)
    {
        $dateToday = getdate();
        $month = $dateToday['mon'];
        $day = $dateToday['mday'];
        $year = $dateToday['year'];
        
        //lastmonth 16 to 30/31
        if( $day <= 5 ){ 
            $startDate = date( "Y-m-d H:i:s", mktime(0,0,0,$month-1,16,$year) );
            $endDate = date( "Y-m-d H:i:s", mktime(23,59,59,$month-1,date('d', strtotime('last day of previous month')),$year) );
            $payoutDate = date("Y-m-d", mktime(0,0,0,$month,5,$year));
        }
        else if( $day > 20 ){
            $startDate = date( "Y-m-d H:i:s", mktime(0,0,0,$month,16,$year) );
            $endDate = date( "Y-m-d H:i:s", mktime(23,59,59,$month,date('t'),$year) );
            $payoutDate = date("Y-m-d", mktime(0,0,0,$month+1,5,$year));
        // thismonth 1st to 15th
        }
        else if( $day > 5 && $day <= 20 ){
            $startDate = date( "Y-m-d H:i:s", mktime(0,0,0,$month,1,$year) );
            $endDate = date( "Y-m-d H:i:s", mktime(23,59,59,$month,15,$year) );
            $payoutDate = date("Y-m-d", mktime(0,0,0,$month,20,$year));
        }
        
        $query = $this->xmlmap->getFilenameID('sql/users','getNextPayout');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id);
        $sth->bindParam(':start_date',$startDate);
        $sth->bindParam(':end_date',$endDate);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array(
            'list' => array(),
            'payout' => 0,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'payout_date' => $payoutDate
        );
        
        foreach($row as $r){
            if( !isset($data['list'][$r['order_id']]) ){
                $data['list'][$r['order_id']]['invoice'] = $r['invoice_no'];
                $data['list'][$r['order_id']]['tx_net'] = 0;
                $data['list'][$r['order_id']]['product'] = array();
            }
            
            $data['list'][$r['order_id']]['product'][] = array(
                'name' => $r['name'],
                'qty' => $r['order_quantity'],
                'base_price' => $r['price'],
                'handling_fee' => $r['handling_fee'],
                'prd_total_price' => $r['total'],
                'payment_method_charge' => $r['payment_method_charge'],
                'easyshop_charge' => $r['easyshop_charge'],
                'prd_net' => $r['net']
            );
            
            $data['payout'] += $r['net'];
            $data['list'][$r['order_id']]['tx_net'] += $r['net'];
        }
        
        return $data;
    }
    
    /**
     *  Fetch all transactions where payment is not yet forwarded to user
     */
    public function getUserBalance($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getUserBalance');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        $data = array(
            'list' => array(),
            'balance' => 0
        );
        
        foreach($row as $r){
            if( !isset($data['list'][$r['order_id']]) ){
                $data['list'][$r['order_id']]['invoice'] = $r['invoice_no'];
                $data['list'][$r['order_id']]['tx_net'] = 0;
                $data['list'][$r['order_id']]['product'] = array();
            }
            
            $data['list'][$r['order_id']]['product'][] = array(
                'name' => $r['name'],
                'qty' => $r['order_quantity'],
                'base_price' => $r['price'],
                'handling_fee' => $r['handling_fee'],
                'prd_total_price' => $r['total'],
                'payment_method_charge' => $r['payment_method_charge'],
                'easyshop_charge' => $r['easyshop_charge'],
                'prd_net' => $r['net']
            );
            
            $data['balance'] += $r['net'];
            $data['list'][$r['order_id']]['tx_net'] += $r['net'];
        }
        
        return $data;
    }
    
    /***********    NEW VENDOR FUNCTIONS    *****************/
    
    /**
     *  Function for fetching subscription
     *  
     *  @return followed, unfollowed, or error
     */
    public function checkVendorSubscription($member_id, $sellername)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','checkVendorSubscription');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id, PDO::PARAM_INT);
        $sth->bindParam(':sellername',$sellername, PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        if( isset($row['vendor_id']) ){ //if seller exists and is not the same user
            if( (int)$row['member_id'] === 0 ) {# no entry - unfollowed
                $result['stat'] = 'unfollowed';
            }
            else if( (int)$row['member_id'] !== 0 ) { #has entry - followed
                $result['stat'] = 'followed';
            }
            $result['vendor_id'] = $row['vendor_id'];
        }
        else{
            $result['stat'] = 'error';
        }
        
        return $result;
    }
    
    /**
     *  Fetch number of subscribed users for a certain user
     */
    public function countVendorSubscription($member_id, $sellername)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','countVendorSubscription');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id, PDO::PARAM_INT);
        $sth->bindParam(':sellername',$sellername, PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }
    
    /**
     *  Used to subscribe or unsubscribe
     */
    public function setVendorSubscription($member_id, $vendor_id, $method)
    {
        if($method === 'unfollowed'){ #then follow
            $query = $this->xmlmap->getFilenameID('sql/users','insertVendorSubscription');
        }
        else if($method === 'followed'){ #then unfollow
            $query = $this->xmlmap->getFilenameID('sql/users','deleteVendorSubscription');
        }
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id, PDO::PARAM_INT);
        $sth->bindParam(':vendor_id',$vendor_id, PDO::PARAM_INT);
        $boolResult = $sth->execute();
        
        return $boolResult;
    }
    
    /**
     *  Used to update store description
     *
     *  @return TRUE on success, FALSE on result
     */
    public function updateStoreDesc($member_id, $desc)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','updateStoreDesc');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':store_desc',$desc, PDO::PARAM_STR);
        $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $boolResult = $sth->execute();
        
        return $boolResult;
    }
    
    /**
     *  Fetch vendor products in vendor page
     *
     *  @param integer $member_id
     *  @param string $username
     *
     *  @return array
     */
    public function getVendorCatItems($member_id, $username)
    {
        $categoryItemCount = 12;
        $otherItemCount = 12;
        $categoryProductCount = 4;
        $defaultCatImg = "assets/images/default_icon_small.png";
    
        $query = $this->xmlmap->getFilenameID('sql/product','getVendorProdCatDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $sth->execute();
        $vendorCategories = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        
        foreach( $vendorCategories as $vendorCategory ){
            if( !isset($data[$vendorCategory['parent_cat']]) ){
                $catImg = "assets/" . substr($vendorCategory['p_cat_img'],0,strrpos($vendorCategory['p_cat_img'],'.')) . "_small.png";
                if( $vendorCategory['p_cat_img'] !== "" && file_exists($catImg)){
                    $categoryImage = $catImg;
                }
                else{
                    $categoryImage = $defaultCatImg;
                }
                
                $data[$vendorCategory['parent_cat']] = array(
                    'name' => $vendorCategory['p_cat_name'],
                    'slug' => $vendorCategory['p_cat_slug'],
                    'child_cat' => array(),
                    'products' => array(),
                    'count' => 0,
                    'loadmore_link' => '/advsrch?_us=' . $username . '&_cat=' . $vendorCategory['parent_cat'],
                    'cat_link' => '/category/' . $vendorCategory['p_cat_slug'],
                    'cat_img' => $categoryImage
                );
            }
            $data[$vendorCategory['parent_cat']]['child_cat'][] = $vendorCategory['cat_id'];
            $data[$vendorCategory['parent_cat']]['count'] += $vendorCategory['prd_count'];
        }
        
        $temp = array();
        $otherCount = 0;
        
        foreach($data as $k=>$d){
            #If category has more or equal to categoryProductCount - to be displayed as specific category
            if( (int)$d['count'] >= $categoryProductCount && (int)$k !== 1){
                $parseData['in_condition'] = implode(',',$d['child_cat']);
                
                $query = $this->xmlmap->getFilenameID('sql/product','getTopXCatItems');
                $query = $this->parser->parse_string($query, $parseData, true);
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
                $sth->bindParam(':item_count', $categoryItemCount, PDO::PARAM_INT);
                $sth->execute();
                $products = $sth->fetchAll(PDO::FETCH_ASSOC);
                
                foreach( $products as $p ){
                    $imagepath[0] = array(
                        'product_image_path' => $p['product_image_path']
                    );
                    explodeImagePath($imagepath);
                    $p['product_image_path'] = $imagepath[0]['path'] . 'categoryview/' . $imagepath[0]['file'];
                
                    array_push( $data[$k]['products'], $p );
                }
            #If less than categoryProductCount, push all child cat into temp array
            }
            else{
                $temp = array_merge($temp, $d['child_cat']);
                $otherCount += $d['count'];
                unset($data[$k]);
            }
        }
        
        #If temp array has value, get top products to be categorized as others
        if( count($temp) > 0 ){
            $parseData['in_condition'] = implode(',',$temp);
            
            array_push($data, array(
                'name' => 'Others',
                'slug' => 'others',
                'child_cat' => $temp,
                'products' => array(),
                'count' => $otherCount,
                'loadmore_link' => '/advsrch?_us=' . $username,
                'cat_link' => '',
                'cat_img' => $defaultCatImg
            ));
            
            end($data);
            $last_id = key($data);
            
            $query = $this->xmlmap->getFilenameID('sql/product','getTopXCatItems');
            $query = $this->parser->parse_string($query, $parseData, true);
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $sth->bindParam(':item_count', $otherItemCount, PDO::PARAM_INT);
            $sth->execute();
            $products = $sth->fetchAll(PDO::FETCH_ASSOC);
            
            foreach( $products as $p ){
                $imagepath[0] = array(
                    'product_image_path' => $p['product_image_path']
                );
                explodeImagePath($imagepath);
                $p['product_image_path'] = $imagepath[0]['path'] . 'categoryview/' . $imagepath[0]['file'];
                
                array_push( $data[$last_id]['products'], $p );
            }
        }
        
        return $data;
    }
    
    /**
     *  Used to check if slug is available or not. Check for username and userslug
     *
     *  @return $row that matches userslug
     */
    public function validateUserSlugChange( $memberID, $userslug )
    {
        $query = "SELECT id_member
            FROM es_member
            WHERE (username = :userslug OR slug = :userslug) AND id_member != :member_id";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $memberID, PDO::PARAM_INT);
        $sth->bindParam(':userslug', $userslug, PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;
    }
    
    /**
     *  Used to edit userslug
     *
     *  @param integer $memberID
     *  @param string $userslug
     *  
     */
    public function editUserSlug($memberID, $userslug)
    {
        $query = "UPDATE `es_member` SET slug = :userslug WHERE id_member = :member_id";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $memberID, PDO::PARAM_INT);
        $sth->bindParam(':userslug', $userslug, PDO::PARAM_STR);
        $result = $sth->execute();
        
        return $result;
    }

}

/* End of file memberpage_model.php */
/* Location: ./application/models/memberpage_model.php */
