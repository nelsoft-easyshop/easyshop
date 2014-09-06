<?php

namespace EasyShop\Cart;

interface CartInterface
{
    public function getContents();
    
    public function getSize($isUnique);
    
    public function getTotalPrice();
    
    public function persist($memberId);
    
    public function addContent($data);
    
    public function removeContent($cartId);
    
    public function getIndexName();

}
