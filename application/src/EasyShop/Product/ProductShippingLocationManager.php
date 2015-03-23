<?php

namespace EasyShop\Product;

use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\Entities\EsLocationLookup as EsLocationLookup;

/**
 * Product Shipping Location Manager Class
 *
 */
class ProductShippingLocationManager
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;


    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Get product shipping information summary
     * @param  integer $productId
     * @return mixed
     */
    public function getProductShippingSummary($productId)
    {
        $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->getProductShippingDetails($productId);

          $data = [
            'has_shippingsummary' => false,
            'is_freeshipping' => false,
            'location_lookup' => [],
            'shipping_locations' => [],
            'shipping_display' => [
                ''=> [
                    'location' => [ '' => [''=>''] ],
                    'attr' => [''=>''],
                    'disable_lookup' => []
                ]
            ]
        ];

        $locationPriceArray = [];

        $deliveryCount = $summaryCount = $freeCount = 0;

        foreach($shippingDetails as $detail){
            $pid = (int)$detail['id_product_item'];
            $locid = (int)$detail['id_location'];
            $price = (int)$detail['price'];

            if( !isset($data['shipping_locations'][$pid]) ){
                $data['shipping_locations'][$pid] = [];
            }
            $data['location_lookup'][$detail['id_location']] = $detail['location'];

            if( $locid === 0 && $price === 0 ){
                $deliveryCount++;
            }
            else if( $locid === 1 && $price === 0 ){
                $freeCount++;
            }
            else{
                $summaryCount++;
            }
        }

        $data['is_delivery'] = count($shippingDetails) === $deliveryCount ? false:true;

        if( $data['is_delivery'] ){
            if( $freeCount > $summaryCount ){
                $data['is_freeshipping'] = true;
            }
            else if( $summaryCount > $freeCount ){
                $data['has_shippingsummary'] = true;
            }

            if( $data['has_shippingsummary'] ){

                foreach( $shippingDetails as $detail ){
                    $pid = (int)$detail["id_product_item"];
                    $loc = (int)$detail["id_location"];
                    $price = number_format($detail["price"], 2, '.', ',');
                    if( $loc !== 0 && $loc !== 1 ){
                        if( !in_array($loc, $data['shipping_locations'][$pid]) ){
                            $data['shipping_locations'][$pid][] = $loc;
                        }
                        if( !isset( $locationPriceArray[$pid][$loc] ) ){
                            $locationPriceArray[$pid][$loc] = $price;
                        }
                    }
                }

                $arr1 = $locationPriceArray;
                $arr2 = $locationPriceArray;

                $finalarr = [];

                foreach( $arr1 as $attr1=>$t1 ){
                    do{
                        $isFound = false;
                        $minIntersectCount = 0;
                        $intersectArray = [];
 
                        foreach( $arr2 as $attr2=>$t2 ){
                            if( $attr1 === $attr2 ){
                                continue;
                            }
                            else{ 
                                $temp1 = array_intersect_assoc($t1, $t2); 
                                if( count($temp1) > 0 ){
                                    $isFound = true;
                                    if( $minIntersectCount === 0 || count($temp1) < $minIntersectCount ){
                                        $minIntersectCount = count($temp1);
                                        $intersectArray['location'] = $temp1;
                                    }
                                }
                            }
                        }
                        if($isFound){
                            $intersectArray['attr'] = [];
                            foreach( $arr2 as $attr2=>$t2 ){
                                $arrIntersectDiff = array_diff_assoc($intersectArray['location'],$t2);
                                if( count($arrIntersectDiff)===0 ){
                                    $intersectArray['attr'][] = $attr2;
                                }
                            }
                            $isExist = $hasDuplicate = false; 
                            foreach( $finalarr as $fkey=>$farr ){
                                $sizeOfIntersect = count($intersectArray['location']);
                                $sizeOfLocGroup = count($farr['location']);

                                if( $sizeOfLocGroup > $sizeOfIntersect ){
                                    $sizeOfDiff = count(array_diff_assoc($farr['location'],$intersectArray['location']));
                                    $sizeOfLarger = $sizeOfLocGroup;
                                }
                                else{
                                    $sizeOfDiff = count(array_diff_assoc($intersectArray['location'],$farr['location']));
                                    $sizeOfLarger = $sizeOfIntersect;
                                }

                                if( $sizeOfDiff === 0 && $sizeOfLocGroup === $sizeOfIntersect ){
                                    if( !in_array($attr1, $farr['attr']) ){
                                        $finalarr[$fkey]['attr'][] = $attr1;
                                    }

                                    $attrDiff = array_diff($intersectArray['attr'],$farr['attr']);
                                    if( count($attrDiff) > 0 ){
                                        $finalarr[$fkey]['attr'] = array_merge($farr['attr'], $intersectArray['attr']);
                                    }
                                    $isExist = true;
                                    break; 
                                }
                                else if( $sizeOfDiff !== 0 && $sizeOfDiff !== $sizeOfLarger ){
                                    $hasDuplicate = true;
                                    break;
                                }
                            }

                            if(!$isExist && !$hasDuplicate){
                                $finalarr[] = $intersectArray;
                            }

                            $t1 = array_diff_assoc($t1,$intersectArray['location']);
                            foreach( $intersectArray['attr'] as $ik ){
                                $arr2[$ik] = array_diff_assoc($arr2[$ik],$intersectArray['location']);
                            }
                        }
                    }
                    while($isFound && count($t1)>0 );
                }

                foreach($arr2 as $attrk=>$locpricearr){
                    if(count($locpricearr) > 0){
                        $finalarr[] = [
                            'location' => $locpricearr,
                            'attr' => [$attrk]
                        ];
                    }
                }

                foreach( $finalarr as $fkey=>$farr ){
                    $locPriceFilter = [];
                    $disablearr = [];
                    foreach($farr['location'] as $locid=>$price){
                        if( !isset($locPriceFilter[$price]) ){
                            $locPriceFilter[$price] = [];
                        }
                        if( !in_array($locid, $locPriceFilter[$price]) ){
                            $locPriceFilter[$price][] = $locid;
                        }
                        if( !in_array($locid,$disablearr) ){
                            $disablearr[] = $locid;
                        }
                    }
                    $finalarr[$fkey]['location'] = $locPriceFilter;
                    $finalarr[$fkey]['disable_lookup'] = $disablearr;
                }
                $data['shipping_display'] = $finalarr;
            }
        }

        return $data;
    }

    /**
     * get product item attribute
     * @param  integer $productId
     * @return mixed
     */
    public function getShippingAttribute($productId)
    {
        $productAttr = $this->em->getRepository('EasyShop\Entities\EsProductItem')
                                ->getProductShippingAttribute($productId);

        $data['has_attr'] = false;
        if(!empty($productAttr)){ 
            foreach($productAttr as $attr){
                if($attr['id_product_item'] && $attr['attr_value']){
                    $data['attributes'][$attr['id_product_item']][] = [
                        'name' => $attr['name'],
                        'value' => $attr['attr_value']
                    ];
                }
            }
            $data['has_attr'] = true;
        }

        return $data;
    }

    /**
     * Delete all shipping information of individual product
     * @param  integer $productId
     */
    public function deleteProductShippingInfo($productId)
    {   
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->find($productId);
        
        if(!$product){
            throw new Exception("Product not exists.");
        }

        $shippingHeads = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead')
                                  ->findBy([
                                      'product' => $productId
                                  ]);

        foreach ($shippingHeads as $head) {
            $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                        ->findBy([
                                            'shipping' => $head->getIdShipping()
                                        ]);
            foreach ($shippingDetails as $detail) {
                $this->em->remove($detail);
            }
            $this->em->remove($head);
        }
        $this->em->flush();
    }

    /**
     * Get product item shipping fee
     * @param  integer $itemId
     * @param  integer $regionId
     * @param  integer $cityId
     * @param  integer $islandId
     * @return float
     */
    public function getProductItemShippingFee($itemId, $regionId, $cityId, $islandId)
    {
        $itemLocations = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                              ->findBy([
                                'productItem' => $itemId,
                              ]);

        $locationArray = [];
        foreach ($itemLocations as $location) {
            $locationId = $location->getShipping()->getLocation()->getIdLocation();
            $locationArray[$locationId] = $location->getShipping()->getPrice();
        } 

        /**
         * Return the shipping fee from the most specific location
         */
        if(array_key_exists($regionId, $locationArray)){
            return (float)$locationArray[$regionId];
        }
        elseif (array_key_exists($cityId, $locationArray)) {
            return (float)$locationArray[$cityId];
        }
        elseif (array_key_exists($islandId, $locationArray)) {
            return (float)$locationArray[$islandId];
        }
        elseif (array_key_exists(EsLocationLookup::PHILIPPINES_LOCATION_ID, $locationArray)) {
            return (float)$locationArray[EsLocationLookup::PHILIPPINES_LOCATION_ID];
        }
        else{
            return null;
        }
    }

}

