<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class payment_model extends CI_Model
{
    function __construct() 
    {
        parent::__construct();
        $this->load->library("xmlmap");
        $this->load->model('cart_model');
        $this->load->model('user_model');
        $this->load->model('product_model');
    }   


    function getUserAddress($member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment', 'get_address');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member', $member_id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        return $row;
        
    }

    function payment($paymentType,$ItemTotalPrice,$member_id,$productstring,$productCount,$apiResponse,$tid)
    {

        $invoice_no = $member_id.'-'.date('ymdhs'); 
        $ip = $this->user_model->getRealIpAddr();  

        $query = $this->xmlmap->getFilenameID('sql/payment','payment_transaction');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':payment_type',$paymentType,PDO::PARAM_INT);
        $sth->bindParam(':invoice_no',$invoice_no,PDO::PARAM_STR);
        $sth->bindParam(':total_amt',$ItemTotalPrice,PDO::PARAM_STR);
        $sth->bindParam(':ip',$ip,PDO::PARAM_STR);
        $sth->bindParam(':member_id',$member_id,PDO::PARAM_INT);
        $sth->bindParam(':string',$productstring,PDO::PARAM_STR);
        $sth->bindParam(':product_count',$productCount,PDO::PARAM_INT);
        $sth->bindParam(':data_response',$apiResponse,PDO::PARAM_STR);
        $sth->bindParam(':tid',$tid,PDO::PARAM_STR);
        $sth->bindParam(':dateadded', date('Y-m-d H:i:s'),PDO::PARAM_STR); 

        $sth->execute();
        
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
        
    }

    function checkMyDp($transactionId)
    {
        $query = "CALL es_sp_checkDP(:tid)";

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':tid',$transactionId,PDO::PARAM_STR); 
        $sth->execute();
    }

    public function releaseAllLock($member_id)
    {
        $query = "
        DELETE 
        FROM
            `es_product_item_lock` 
        WHERE id_item_lock IN 
            (SELECT 
            * FROM
            (SELECT 
                b.`id_item_lock` 
            FROM
                es_order a
                , `es_product_item_lock` b 
            WHERE a.buyer_id = :member_id 
                AND a.`order_status` = 99 
                AND a.`id_order` = b.`order_id`) AS tbl)
        ";

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id,PDO::PARAM_INT); 
        $sth->execute();
    }

    public function lockcount($orderId)
    {
        $query = "Select count(*) as cnt from `es_product_item_lock` where order_id = :order_id limit 1";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':order_id',$orderId,PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row[0]['cnt'];

    }
    
    public function lockItem($itemId,$qty,$orderId,$action)
    {
    
        if($action == 'delete'){
            $query = "
                DELETE 
                FROM
                    es_product_item_lock 
                WHERE product_item_id = :item_id 
                    AND qty = :quantity
                    AND order_id = :order_id
            ";
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':item_id',$itemId,PDO::PARAM_INT);
            $sth->bindParam(':quantity',$qty,PDO::PARAM_INT); 
            $sth->bindParam(':order_id',$orderId,PDO::PARAM_INT); 
        }
        else{
            $query = "INSERT INTO `es_product_item_lock` (order_id,product_item_id, qty, timestamp) VALUES 
                    (:order_id, :item_id, :quantity, :datenow)";
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $sth->bindParam(':quantity', $qty, PDO::PARAM_INT); 
            $sth->bindParam(':order_id', $orderId, PDO::PARAM_INT); 
            $sth->bindParam(':datenow', date('Y-m-d H:i:s')); 
        }
 
        if ($sth->execute()){
            return 1;
        }
        else{
            return 0;
        }
    }


    function deductQuantity($productId,$itemId,$qty)
    {
        $query = "
        UPDATE 
            `es_product_item` 
        SET
            `quantity` = `quantity` - :quantity 
        WHERE `product_id` = :product_id 
            AND `id_product_item` = :item_id;
        ";
        ;
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':quantity',$qty,PDO::PARAM_INT);
        $sth->bindParam(':product_id',$productId,PDO::PARAM_INT);
        $sth->bindParam(':item_id',$itemId,PDO::PARAM_INT);
        
        if ($sth->execute()){
            // success
            return 1;
        }
        else{
            return 0;
        }
    }

    function selectFromEsOrder($token,$paymentType)
    {
        $query = "SELECT invoice_no,id_order,dateadded,buyer_id,data_response,postbackcount,total,order_status FROM es_order WHERE transaction_id = :token AND payment_method_id = :payment_id";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':token',$token,PDO::PARAM_STR);
        $sth->bindParam(':payment_id',$paymentType,PDO::PARAM_STR);
        $sth->execute();
        
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;

    }

    function updatePaymentIfComplete($id,$data,$tid,$paymentType,$orderStatus = 99,$flag = 0)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','updatePaymentIfComplete');
        $sth = $this->db->conn_id->prepare($query);

        $sth->bindParam(':order_status',$orderStatus,PDO::PARAM_STR);
        $sth->bindParam(':data',$data,PDO::PARAM_STR);
        $sth->bindParam(':id_order',$id,PDO::PARAM_INT);
        $sth->bindParam(':tid',$tid,PDO::PARAM_STR);
        $sth->bindParam(':payment_id',$paymentType,PDO::PARAM_STR);
        $sth->bindParam(':flag',$flag,PDO::PARAM_STR);
        
        if ($sth->execute()){
            // success
            return 1;
        }
        else{
            return 0;
        }
    }

    function updateFlag($txnId)
    {
        $newValue = '%'.$txnId.'%';
        $query = 'UPDATE es_order set is_flag = 0 WHERE transaction_id like :txnid';

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':txnid',$newValue,PDO::PARAM_STR);
        
        if ($sth->execute()){
            // success
            return 1;
        }
        else{
            return 0;
        }
    }

    function cancelTransaction($txnId,$quantity = true)
    {

        if($txnId == ''){
            
            return 0;
        }

        $newValue = '%'.$txnId.'%';
        $query = 'UPDATE es_order set order_status = 2 WHERE transaction_id = :txnid';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':txnid',$txnId,PDO::PARAM_STR);
        if ($sth->execute()){ 

            $query = 'SELECT id_order from es_order where transaction_id = :txnid LIMIT 1'; 
            $sth2 = $this->db->conn_id->prepare($query);
            $sth2->bindParam(':txnid',$txnId,PDO::PARAM_STR);
            $sth2->execute();
            $row = $sth2->fetch(PDO::FETCH_ASSOC); 

            $orderId = $row['id_order']; 
            $query = 'SELECT * from es_order_product where order_id = :order_id';
            $sth3 = $this->db->conn_id->prepare($query);
            $sth3->bindParam(':order_id',$orderId,PDO::PARAM_INT);
            $sth3->execute();

            $orderProduct = $sth3->fetchAll(PDO::FETCH_ASSOC); 
            
            if($quantity){
                foreach ($orderProduct as $key => $value) {
                    $query = 'UPDATE es_product_item set quantity = quantity + :returnqty where id_product_item = :item_id';
                    $sth3 = $this->db->conn_id->prepare($query);
                    $sth3->bindParam(':returnqty',$value['order_quantity'],PDO::PARAM_INT);
                    $sth3->bindParam(':item_id',$value['product_item_id'],PDO::PARAM_INT);
                    $sth3->execute();

                    $this->product_model->update_soldout_status($value['product_id']);

                    $historyData = array(
                        'order_product_id' => $value['product_item_id'],
                        'order_product_status' => '6',
                        'comment' => 'REJECTED'
                        );
                    
                    $this->addOrderProductHistory($historyData);
                }
            }

            $query = 'UPDATE es_order_product set status = 6 WHERE order_id = :order_id';
            $sth4 = $this->db->conn_id->prepare($query);
            $sth4->bindParam(':order_id',$orderId,PDO::PARAM_INT);
            $sth4->execute(); 
            return $orderId;
            
        } 
            
    }


    public function sendNotificationEmail($data, $email, $string)
    {
        $this->load->library('email');	
        $this->load->library('parser');
        $this->email->set_newline("\r\n");
        $this->email->from('noreply@easyshop.ph', 'Easyshop.ph');
        $workingDirectory = getcwd();
        $this->email->attach($workingDirectory. "/assets/images/landingpage/templates/header-img.png", "inline");
        $this->email->attach($workingDirectory. "/assets/images/appbar.home.png", "inline");
        $this->email->attach($workingDirectory. "/assets/images/appbar.message.png", "inline");
        $this->email->attach($workingDirectory. "/assets/images/landingpage/templates/facebook.png", "inline");
        $this->email->attach($workingDirectory. "/assets/images/landingpage/templates/twitter.png", "inline");

        switch($string){
            case 'buyer':
                $this->email->subject($this->lang->line('notification_subject_buyer'));
                #user appended at template
                $data['store_link'] = base_url();
                $data['msg_link'] = base_url() . "messages/#";
                $msg = $this->parser->parse('emails/email_purchase_notification_buyer',$data,true);
                break;
            case 'seller':
                $this->email->subject($this->lang->line('notification_subject_seller'));
                $data['store_link'] = base_url() . $data['buyer_slug'];
                $data['msg_link'] = base_url() . "messages/#" . $data['buyer_name'];
                $msg = $this->parser->parse('emails/email_purchase_notification_seller',$data,true);
                break;
            case 'return_payment':
                $this->email->subject($this->lang->line('notification_returntobuyer'));
                $data['store_link'] = base_url() . "vendor/" . $data['user'];
                $data['msg_link'] = base_url() . "messages/#" . $data['user'];
                $msg = $this->parser->parse('templates/email_returntobuyer',$data,true);
                break;
        }
        
        $this->email->to($email);
        
        $this->email->message($msg);
        $result = $this->email->send();

        $errmsg = $this->email->print_debugger();
        return $result;
    }

    /*
        *Code	Description
        *200	Successfully Sent
        *201	Message Queued
        *100	Not Authorized
        *101	Not Enough Balance
        *102	Feature Not Allowed
        *103	Invalid Options
        *104	Gateway Down
        */
    function sendNotificationMobile($mobile, $msg)
    {
        return true;
        
        
        $fields = array();
        $fields["api"] = "dgsMQ8q77hewW766aqxK";
        
        //$fields["number"] = 9177050441; //safe use 63
        $fields["number"] = $mobile;
        
        $fields["message"] = $msg;
        $fields["from"] = 'Easyshop.ph';
        $fields_string = http_build_query($fields);
        $outbound_endpoint = "http://api.semaphore.co/api/sms";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $outbound_endpoint);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        
        return $output;
    }

    /*
        *	Function to get Transaction Details for summary in notification email
        *	Sent right after transaction is made
        *  $data = array(
        *		'member_id' => Member ID who made the purchase (buyerID)
        *		'order_id'	=> Transaction Number
        *		'invoice_no' => Invoice number
        *	)
        */
    public function getPurchaseTransactionDetails($data)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','getPurchaseTransactionDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':buyer_id',$data['member_id']);
        $sth->bindParam(':order_id',$data['order_id']);
        $sth->bindParam(':invoice_no', $data['invoice_no']);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        $buyerStore = $row[0]['buyer_store'] === "" || is_null($row[0]['buyer_store']) 
                      ? $row[0]['buyer'] 
                      : $row[0]['buyer_store'];

        $data = array(
            'id_order' => $row[0]['id_order'],
            'dateadded' => $row[0]['dateadded'],
            'buyer_name' => $row[0]['buyer'],
            'buyer_slug' => $row[0]['buyer_slug'],
            'buyer_email' => $row[0]['buyer_email'],
            'buyer_contactno' => $row[0]['buyer_contactno'],
            'totalprice' => $row[0]['totalprice'],
            'invoice_no' => $row[0]['invoice_no'],
            'payment_method' => (int)$row[0]['payment_method_id'],
            'buyer_store' => $buyerStore,
            'products' => [],
        );

        foreach($row as $value){
            $temp = $value;
            $sellerStore = $value['seller_store'] === "" || is_null($value['seller_store'])
                           ? $value['seller']
                           : $value['seller_store'];

            // Assemble data for buyer
            if(!isset($data['products'][$value['id_order_product']])){
                $data['products'][$value['id_order_product']] = $temp;
                $data['products'][$value['id_order_product']]['order_product_id'] = $temp['id_order_product'];
                $data['products'][$value['id_order_product']]['seller_slug'] = $value['seller_slug'];
                $data['products'][$value['id_order_product']]['seller_store'] = $sellerStore;
                $data['products'][$value['id_order_product']]['buyer_store'] = $buyerStore;
            }
            
            // Assemble data for seller
            if(!isset($data['seller'][$value['seller_id']])){
                $data['seller'][$value['seller_id']]['email'] = $value['seller_email'];
                $data['seller'][$value['seller_id']]['seller_name'] = $value['seller'];
                $data['seller'][$value['seller_id']]['totalprice'] = 0;
                $data['seller'][$value['seller_id']] = array_merge( $data['seller'][$value['seller_id']], $temp );
                $data['seller'][$value['seller_id']]['seller_store'] = $sellerStore;
                $data['seller'][$value['seller_id']]['buyer_store'] = $buyerStore;
            }
            if(!isset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']])){
                $data['seller'][$value['seller_id']]['products'][$value['id_order_product']] = $temp;
                $data['seller'][$value['seller_id']]['totalprice'] += preg_replace('/\,/', '' , $value['net']);
                $data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['order_product_id'] = $value['id_order_product'];
            }
            
            // Assemble attr array for buyer and seller
            if(!isset($data['products'][$value['id_order_product']]['attr'])){
                $data['products'][$value['id_order_product']]['attr'] = [];
            }
            if(!isset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'])){
                $data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'] = [];
            }
            if( (string)$temp['attr_name']!=='' && (string)$temp['attr_value']!=='' ){
                array_push($data['products'][$value['id_order_product']]['attr'], array('attr_name' => $temp['attr_name'],'attr_value' => $temp['attr_value']));
                array_push($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'], array('attr_name' => $temp['attr_name'],'attr_value' => $temp['attr_value']));
            }
            else{
                array_push($data['products'][$value['id_order_product']]['attr'], array('attr_name' => 'Attribute', 'attr_value' => 'N/A'));
                array_push($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr'], array('attr_name' => 'Attribute','attr_value' => 'N/A'));
            }

            unset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr_name']);
            unset($data['seller'][$value['seller_id']]['products'][$value['id_order_product']]['attr_value']);
            unset($data['seller'][$value['seller_id']]['attr_name']);
            unset($data['seller'][$value['seller_id']]['attr_value']);
            unset($data['products'][$value['id_order_product']]['attr_name']);
            unset($data['products'][$value['id_order_product']]['attr_value']);
        }
        return $data;
    }

    /*
        *	Function to get product order transaction details for email notification upon transaction response
        *	Used by memberpage->transactionResponse function 
        */
    public function getOrderProductTransactionDetails($data)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','getOrderProductTransactionDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':order_product_id', $data['order_product_id']);
        $sth->bindParam(':order_id', $data['transaction_num']);
        $sth->bindParam(':invoice_num', $data['invoice_num']);
        $sth->bindParam(':member_id', $data['member_id']);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        $parseData = array_splice($row[0], 1, 10);
        $parseData['attr'] = array();
        
        if($data['status'] === 1){ // if forward to seller (email to seller)
            $parseData['user'] = $row[0]['buyer'];
            $parseData['user_slug'] = $row[0]['buyer_slug'];
            $parseData['email'] = $row[0]['seller_email'];
            $parseData['mobile'] = trim($row[0]['seller_contactno']);
            $parseData['recipient'] = $row[0]['seller'];
        } 
        else if($data['status'] === 2 || $data['status'] === 3){ // if return to buyer or COD (email to buyer)
            $parseData['user'] = $row[0]['seller'];
            $parseData['user_slug'] = $row[0]['seller_slug'];
            $parseData['email'] = $row[0]['buyer_email'];
            $parseData['mobile'] = trim($row[0]['buyer_contactno']);
            $parseData['recipient'] = $row[0]['buyer'];
        }
        
        switch( (int)$row[0]['payment_method_id'] ){
            case 1:
                $parseData['payment_method_name'] = "PayPal";
                break;
            case 2:
                $parseData['payment_method_name'] = "DragonPay";
                break;
            case 3:
                $parseData['payment_method_name'] = "Cash on Delivery";
                break;
            case 5:
                $parseData['payment_method_name'] = "Bank Deposit";
                break;
        }
        
        foreach( $row as $r){
            if( (string)$r['attr_name']!=='' && (string)$r['attr_value']!=='' ){
                array_push($parseData['attr'], array('field' => ucwords(strtolower($r['attr_name'])), 'value' => ucwords(strtolower($r['attr_value'])) ));
            }else{
                array_push($parseData['attr'], array('field' => 'Attribute', 'value' => 'N/A' ));
            }
        }
        
        return $parseData;
    }


    /*	STORED PROCEDURE
        *	Updates es_order_product status
        *	Checks es_order_product status if all orders have buyer/seller response and updates
        *		es_order as complete
        *	USED IN MEMBERPAGE - TRANSACTIONS TAB
        *
        *	Args:
        *		transaction_num, order_product_id, status
        *		member_id
        *
        */
    function updateTransactionStatus($data)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','updateTransactionStatus');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':status', $data['status']);
        $sth->bindParam(':order_product_id', $data['order_product_id']);
        $sth->bindParam(':order_id', $data['transaction_num']);
        $sth->bindParam(':invoice_num', $data['invoice_num']);
        $sth->bindParam(':member_id', $data['member_id']);
        $sth->execute();
        
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }

    function getShippingDetails($product_id ,$product_item_id,$city_id,$region_id,$major_island_id)
    {
        $query = "
            SELECT 
                es_location_lookup.id_location AS id
                , es_location_lookup.location
                , es_location_lookup.`type`
                , es_location_lookup.`parent_id`
                , COALESCE(price, '0') AS price
                , shipping.product_id 
                , COALESCE(product_item_id, 'Not Available') AS product_item_id
                , is_cod 
                , CASE es_location_lookup.`type` 
                WHEN '0' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(1,0)), 'Available', 'Not Avialable')
                WHEN '1' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:major_island_id,0)), 'Available', 'Not Avialable')
                WHEN '2' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:region_id,0)), 'Available', 'Not Avialable')
                WHEN '3' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:city_id,0)), 'Available', 'Not Avialable')
                END AS availability
            FROM
                `es_location_lookup` 
                LEFT OUTER JOIN 
                (
                    SELECT 
                        a.`product_item_id`
                        , b.product_id
                        , c.location AS shipping_location
                        , c.`id_location` AS shipping_id_location
                        , c.`type` AS shipping_type
                        , b.`price` 
                        , d.is_cod 
                    FROM
                        `es_product_shipping_detail` a
                        , `es_product_shipping_head` b
                        , `es_location_lookup` c 
                        , `es_product` d 
                    WHERE b.`id_shipping` = a.`shipping_id` 
                        AND b.`location_id` = c.`id_location` 
                        AND d.`id_product` = b.`product_id` 
                ) AS shipping 
                ON shipping.shipping_id_location = es_location_lookup.`id_location` 
            WHERE es_location_lookup.`type` IN (0, 1, 2, 3) 
                AND COALESCE(product_item_id, 'Not Available') != 'Not Available' 
                AND
                (CASE es_location_lookup.`type` 
                WHEN '0' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(1,0)), 'Available', 'Not Avialable')
                WHEN '1' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:major_island_id,0)), 'Available', 'Not Avialable')
                WHEN '2' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:region_id,0)), 'Available', 'Not Avialable')
                WHEN '3' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:city_id,0)), 'Available', 'Not Avialable')
                END ) = 'Available'
                AND `product_id` = :product_id  
                AND `product_item_id` = :product_item_id
                ORDER BY price ASC LIMIT 1
        ";
        $sth = $this->db->conn_id->prepare($query);

        $sth->bindParam(':product_id', $product_id,PDO::PARAM_INT);
        $sth->bindParam(':product_item_id', $product_item_id,PDO::PARAM_INT); 
        $sth->bindParam(':city_id', $city_id,PDO::PARAM_INT); 
        $sth->bindParam(':region_id', $region_id,PDO::PARAM_INT); 
        $sth->bindParam(':major_island_id', $major_island_id,PDO::PARAM_INT); 
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
            
        return $row ;
    }

    function getCityFromRegion($id_location)
    {
        $query = "
        SELECT * FROM `es_location_lookup` WHERE `type` = 3 AND parent_id = :id_location
        ";
        $sth = $this->db->conn_id->prepare($query);

        $sth->bindParam(':id_location', $id_location,PDO::PARAM_INT); 
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
        
        return $row;
    }

    function getCityOrRegionOrMajorIsland($id_location)
    { 
        $query = "
        SELECT 
            a.*
            , b.location AS parent_location 
        FROM
            `es_location_lookup` a
            , es_location_lookup b 
        WHERE a.`parent_id` = b.`id_location`
        AND a.`id_location` = :id_location
        ";
        $sth = $this->db->conn_id->prepare($query);

        $sth->bindParam(':id_location', $id_location,PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
            
        return $row[0];
    }

    // Used by add feedback - memberpage
    function checkTransaction($temp){
        $query = $this->xmlmap->getFilenameID('sql/payment','checkTransaction');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':buyer', $temp['buyer']);
        $sth->bindParam(':seller', $temp['seller']);
        $sth->bindParam(':order_id', $temp['order_id']);
        $result = $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;
    }

    // Used by dragonpay - memberpage
    function checkTransactionBasic($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','checkTransactionBasic');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':transaction_num', $temp['transaction_num']);
        $sth->bindParam(':invoice_num', $temp['invoice_num']);
        $sth->bindParam(':member_id', $temp['member_id']);
        $result = $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }
    // Check order product before saving comment
    function checkOrderProductBasic($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','checkOrderProductBasic');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_order_product', $temp['order_product']);
        $sth->bindParam(':order_id', $temp['transact_num']);
        $sth->bindParam(':member_id', $temp['member_id']);
        $result = $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;
    }

    // Add shipping comment, binded to order_product
    function addShippingComment($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','addShippingComment');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':order_product', $temp['order_product']);
        $sth->bindParam(':comment', $temp['comment']);
        $sth->bindParam(':member_id', $temp['member_id']);
        $sth->bindParam(':courier', $temp['courier']);
        $sth->bindParam(':tracking_num', $temp['tracking_num']);
        $sth->bindParam(':expected_date', $temp['expected_date']);
        $sth->bindParam(':delivery_date', $temp['delivery_date']);
        $result = $sth->execute();
        
        return $result;
    }

    function addBankDepositDetails($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','addBankDepositDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':order_id', $temp['order_id']);
        $sth->bindParam(':bank', $temp['bank']);
        $sth->bindParam(':ref_num', $temp['ref_num']);
        $sth->bindParam(':amount', $temp['amount']);
        $sth->bindParam(':date_deposit', $temp['date_deposit']);
        $sth->bindParam(':comment', $temp['comment']);
        $result = $sth->execute();
        
        return $result;
    }

    // Used by dragonpay - memberpage
    function updateTransactionStatusBasic($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','updateTransactionStatusBasic');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':transaction_num', $temp['transaction_num']);
        $sth->bindParam(':invoice_num', $temp['invoice_num']);
        $sth->bindParam(':member_id', $temp['member_id']);
        $sth->bindParam(':order_status', $temp['order_status']);
        $result = $sth->execute();
        
        return $result;
    }

    // Function for product rejection. Handles both reject and unreject buttons
    function responseReject($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','responseReject');
        $sth = $this->db->conn_id->prepare($query);
        
        if($temp['method'] === 'reject'){
            $temp['stat'] = 1;
        }else if($temp['method'] === 'unreject'){
            $temp['stat'] = 0;
        }
        
        $sth->bindParam(':stat', $temp['stat']);
        $sth->bindParam(':id_order_product', $temp['order_product']);
        $sth->bindParam(':order_id', $temp['transact_num']);
        $sth->bindParam(':member_id', $temp['member_id']);
        $result = $sth->execute();
        
        return $result;
    }

    // Used by dragonpay - memberpage
    function addOrderHistory($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','addOrderHistory');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':order_id', $temp['order_id']);
        $sth->bindParam(':order_status', $temp['order_status']);
        $sth->bindParam(':comment', $temp['comment']);
        $sth->bindParam(':dateadded', date('Y-m-d H:i:s'),PDO::PARAM_STR); 
        $result = $sth->execute();
        
        return $result;
    }

    function addOrderProductHistory($temp)
    {
        $query = $this->xmlmap->getFilenameID('sql/payment','addOrderProductHistory');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':order_product_id', $temp['order_product_id']);
        $sth->bindParam(':order_product_status', $temp['order_product_status']);
        $sth->bindParam(':comment', $temp['comment']);
        $sth->bindParam(':dateadded', date('Y-m-d H:i:s'),PDO::PARAM_STR); 
        $result = $sth->execute();
        
        return $result;
    }

    public function removeToCart($id,$itemsToDelete)
    {
        $cart_items = $this->cart_model->cartdata($id);

        foreach($cart_items as $c_key => $c_row){
            foreach($itemsToDelete as $i_key => $i_row){
                if($c_key == $i_key){
                    unset($cart_items[$i_key]);
                }
            }
        }
        $result = $this->cart_model->save_cartitems(serialize($cart_items),$id);
        return ($result >= 1 ? true : false);
    }

}


/* End of file payment_model.php */
/* Location: ./application/models/payment_model.php */
