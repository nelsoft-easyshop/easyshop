<?php

namespace EasyShop\Cart;

use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsProduct;

/**
 * Codeigniter implementation of CartInterface
 *
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class CodeigniterCart implements CartInterface
{

    /**
     * The codeigniter cart object
     *
     */
    private $cart;
    
    /**
     * The CI Singleton
     *
     */
    private $CI;
    
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * The name of the CI_CART index
     *
     * @var string
     */
    private $indexName = 'rowid';

    /**
     * Constructor
     * 
     * @param Doctrine\ORM\EntityManager $em
     */
    public function __construct($em)
    {
        $this->CI =& get_instance();
        $this->CI->load->library('cart');
        $this->cart = $this->CI->cart;
        $this->em = $em;    
    }
    
    /**
     * Returns the contents of the cart
     *
     * @return array
     */
    public function getContents()
    {
        return $this->cart->contents();
    }
    
    /**
     * Gets the number of items of the cart
     *
     * @param bool $isUnique
     * @return integer
     */
    public function getSize($isUnique = false)
    {
        if(!$isUnique){
            $size = 0;
            $cartData = $this->cart->contents();
            foreach($cartData as $cartItem){
                $size += $cartItem['qty']; 
            }
            return $size;
        }
        else{
            return sizeof($this->cart->contents());
        }
    }
    
    /**
     * Retrieve total price in the cart
     *
     * @return integer
     */
    public function getTotalPrice()
    {
        $cartData = $this->cart->contents();
        $total = 0;
        foreach($cartData as $cartItem){
           $total += $cartItem['price'] * $cartItem['qty'];
        }
        return number_format($total, 2,'.',',');
    }
    

    
    
    /**
     * Destroys the cart instance
     *
     */
    public function destroy()
    {
        $this->cart->destroy();
    }
    
    /**
     * Add to the cart
     * 
     * @param mixed $data
     * @return bool
     *
     */
    public function addContent($data)
    {
        $data['brief'] = utf8_encode($data['brief'] );
        $data['name'] = utf8_encode($data['name'] );
        return $this->cart->insert($data);
    }
    
    /**
     * Removes an item from the cart
     *
     * @param string $cartId
     * @return string CartId of the inserted item
     */
    public function removeContent($cartId)
    {   
        $dataRemove = array($this->indexName => $cartId, 'qty' => 0);
        return $this->cart->update($dataRemove);
    }
    
    /**
     * Updates cart with id $cartId with data $cartData
     *
     * @param string $cartId
     * @param array $cartData
     * @return bool
     */
    public function updateContent($cartId, $cartData)
    {   
        if(isset($cartData[$this->indexName])){
            $cartData[$this->indexName] = $cartId;
        }
        else{
            $cartData = array_merge($cartData, [$this->indexName => $cartId]);
        }
        $removeData = $cartData;
        $removeData['qty'] = 0;
        $this->cart->update($removeData);

        return $this->cart->insert($cartData);
    }
    
    
    /**
     * Saves the cart contents to the session data in the database
     *
     * @param integer $memberId
     */
    public function persist($memberId)
    {
        $user = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->find($memberId);
                        
        $user->setUserdata(serialize($this->getContents()));
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Returns the name of the cart index
     *
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }
      
    /**
     * Returns a single entry in the cart
     *
     * @param string $cartId
     * @return mixed
     */
    public function getSingleItem($cartId)
    {
        $cartContents = $this->getContents();
        foreach($cartContents as $cartContent){
            if($cartContent[$this->indexName] === $cartId){
                return $cartContent;
            }
        }
        return false;
    }  
      
}

