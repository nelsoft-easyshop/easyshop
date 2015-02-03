<?php
namespace EasyShop\Promo;
use EasyShop\ConfigLoader\ConfigLoader as ConfigLoader;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsPromo;
use EasyShop\Entities\EsPromoType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * PromoManager Class
 *
 */
class PromoManager
{
    /**
     * Codeigniter Config Loader
     *
     * @var EasyShop\CollectionHelper\CollectionHelper
     */
    private $configLoader;

    /**
     * Promo config
     *
     * @var mixed
     */
    private $promoConfig = array();

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Constructor
     * @param ConfigLoader $configLoader
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(ConfigLoader $configLoader, \Doctrine\ORM\EntityManager $em)
    {
        $this->promoConfig = $configLoader->getItem('promo', 'Promo');
        $this->em = $em;
    }

    /**
     * Returns the promo configuration array
     *
     * @param integer $type
     * @return mixed
     */
    public function getPromoConfig($type = null)
    {
        if($type !== null){
            return isset($this->promoConfig[$type]) ? $this->promoConfig[$type] : array();
        }

        return $this->promoConfig;
    }

    /**
     * Hydrates the product entity with promo-related data
     * Product object is modified by reference.
     *
     * @param Product $product
     *
     */
    public function hydratePromoData(&$product)
    {
        $product->setOriginalPrice($product->getPrice());
        $product->setFinalPrice($product->getPrice());
        if(intval($product->getIsPromote()) === 1){
            $promoType = $product->getPromoType();
            if(isset($this->promoConfig[$promoType])){
                if(isset($this->promoConfig[$promoType]['implementation']) &&
                    trim($this->promoConfig[$promoType]['implementation']) !== ''
                ){
                    $promoImplementation = $this->promoConfig[$promoType]['implementation'];
                    $promoOptions = $this->promoConfig[$promoType]['option'];
                    $promoObject = new $promoImplementation($product);
                    $promoObject->setOptions($promoOptions);
                    $product = $promoObject->apply();
                }
            }
        }
        else{
            if(intval($product->getDiscount('discount')) > 0){
                $regularDiscountPrice = $product->getPrice() * (1.0-($product->getDiscount()/100.0));
                $product->setFinalPrice( (floatval($regularDiscountPrice)>0) ? $regularDiscountPrice : 0.01 );
            }
        }
        $percentage = 0;
        if($product->getOriginalPrice() > 0){
            $percentage = 100.00 * ($product->getOriginalPrice() - $product->getFinalPrice())/$product->getOriginalPrice();
        }
        $product->setDiscountPercentage($percentage);

        if (isset($product->isExpired) && $product->isExpired) {
            $product->setIsDelete(EsProduct::DELETE);
            $this->em->flush($product);
        }

    }

    /**
     * Function to calculate the promo price quickly. This is designed for bulk operations within large loops
     *
     * @param integer $productId
     * @return float
     */
    public function hydratePromoDataExpress($productId)
    {
        $productDetails = $this->em->getRepository('EasyShop\Entities\EsProduct')->getRawProductPromoDetails($productId);
        if(!$productDetails){
            return NULL;
        }

        $price = $productDetails['price'];
        $discount = $productDetails['discount'];
        $isPromote = $productDetails['is_promote'];
        $startDate = $productDetails['startdate'];
        $endDate = $productDetails['enddate'];
        $promoType = $productDetails['promo_type'];
        $startDate = date_create($startDate);
        $endDate = date_create($endDate);
        $promoPrice = $price;

        if (intval($isPromote) === 1) {
            if (isset($this->promoConfig[$promoType])) {
                if (isset($this->promoConfig[$promoType]['implementation']) &&
                    trim($this->promoConfig[$promoType]['implementation']) !== ''
                ) {
                    $promoImplementation = $this->promoConfig[$promoType]['implementation'];
                    $promo = $promoImplementation::getPromoData($price, $startDate, $endDate, $discount,$this->promoConfig[$promoType]['option']);
                    $promoPrice = $promo['promoPrice'];
                }
            }
        }
        else {
            if (intval($discount) > 0) {
                $regularDiscountPrice = $price * (1.0-($discount/100.0));
                $promoPrice = (floatval($regularDiscountPrice)>0) ? $regularDiscountPrice : 0.01;
            }
        }

        return $promoPrice;
    }

    /**
     * Returns the product checkout limit based on a promo.
     * This method does not take into consideration which user will buy the
     * the product. Checking if an item can be bought by a particular user is
     * the responsibility of a separate service. Also note that the option
     * quantity limit will take precedence over any other quantity limits.
     *
     * @param Product $product
     * @return integer
     *
     */
    public function getPromoQuantityLimit($product)
    {
        $promoQuantityLimit = PHP_INT_MAX;
        $isPromoActive = $product->getStartPromo();

        if($product->getStartPromo() && $product->getIsPromote()){
            $promoConfig = $this->promoConfig[$product->getPromoType()];
            $promoOptions = $promoConfig['option'];
            $timeNow = strtotime(date('H:i:s'));
            $startDatetime = $product->getStartdate()->getTimestamp();
            $endDatetime = $product->getEnddate()->getTimestamp();

            foreach($promoOptions as $option ){
                if((strtotime($option['start']) <= $timeNow) && (strtotime($option['end']) > $timeNow)){
                    $promoQuantityLimit = $option['purchase_limit'];
                    $startDatetime = date('Y-m-d',strtotime($startDatetime)).' '.$option['start'];
                    $endDatetime = date('Y-m-d',strtotime($endDatetime)).' '.$option['end'];
                    break;
                }
            }

            if(isset($opt['puchase_limit'])){
                $soldCount = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                      ->getSoldCount($product->product_id, $startDatetime, $endDatetime);
                $promoQuantityLimit = $option['purchase_limit'] - $soldCount;
                $promoQuantityLimit = ($promoQuantityLimit >= 0) ? $promoQuantityLimit : 0;
            }
            else{
                $promoQuantityLimit = $promoConfig['purchase_limit'];
            }
        }

        return $promoQuantityLimit;
    }

    /**
     * Promo : BuyAtZero
     * Register member for buy at zero promo
     * @param $productId
     * @param $memberId
     * @return bool
     */
    public function registerMemberForBuyAtZero($productId, $memberId)
    {
        $isAccountRegistered = $this->em->getRepository('EasyShop\Entities\EsPromo')
                                        ->findOneBy([
                                            'productId' => $productId,
                                            'memberId' => $memberId,
                                            'promoType' => EsPromoType::BUY_AT_ZERO
                                        ]);
        if (!$isAccountRegistered) {
            $promo = new EsPromo();
            $promo->setMemberId($memberId);
            $promo->setProductId($productId);
            $promo->setPromoType(EsPromoType::BUY_AT_ZERO);
            $promo->setCreatedAt(new \DateTime('now'));

            $this->em->persist($promo);
            $this->em->flush();
        }

        return (bool) $isAccountRegistered;
    }

    /**
     * Promo : ScratchAndWin
     * validates code and returns the details needed for scratch and win promo
     * @param $code
     * @return array
     */
    public function validateCodeForScratchAndWin($code)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('tblProduct.idProduct, tblPromo.memberId AS c_member_id, tblProductImage.productImagePath as path')
                    ->from('EasyShop\Entities\EsPromo', 'tblPromo')
                    ->leftJoin('EasyShop\Entities\EsProduct', 'tblProduct', 'WITH', 'tblProduct.idProduct = tblPromo.productId')
                    ->leftJoin('EasyShop\Entities\EsProductImage', 'tblProductImage', 'WITH', 'tblProductImage.product = tblProduct.idProduct')
                    ->where('tblPromo.code = :code AND tblPromo.promoType = :promoType')
                    ->setParameter('code', $code)
                    ->setParameter('promoType', EsPromoType::SCRATCH_AND_WIN)
                    ->getQuery();
        $result = $query->getResult();

        if ($result) {
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')->findOneBy(['idProduct' => $result[0]['idProduct']]);
            $isMemberRegistered = $this->em->getRepository('EasyShop\Entities\EsPromo')->findOneBy(['memberId' => $result[0]['c_member_id']]);
            $this->hydratePromoData($product);
            $result = [
                'id_product'=> $product->getIdProduct(),
                'price'=> $product->getPrice(),
                'product' => $product->getName(),
                'brief' => $product->getBrief(),
                'c_id_code' => $result[0]['c_member_id'],
                'can_purchase' => (bool) $isMemberRegistered ? false : true,
                'product_image_path' => $result[0]['path']
            ];
        }

        return $result;
    }

    /**
     * Promo : ScratchAndWin
     * Update member id
     * @param $memberId
     * @param $code
     * @return bool
     */
    public function tieUpCodeToMemberForScratchAndWin($memberId, $code)
    {
        $promo = $this->em->getRepository('EasyShop\Entities\EsPromo')->findOneBy(['code' => $code]);
        $promo->setMemberId($memberId);
        $this->em->persist($promo);
        $this->em->flush();

        return (int) $memberId === (int) $promo->getMemberId();
    }

    /**
     * Promo : TwelveDaysOfChristmas
     * Get active product for twelve days of Christmas promo
     * @param $date
     * @return EsProduct
     */
    public function getActiveDataForTwelveDaysOfChristmasByDate($date)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('tbl_product')
                    ->from('EasyShop\Entities\EsProduct', 'tbl_product')
                    ->where(':dateTime >= tbl_product.startdate')
                    ->andWhere(':dateTime <= tbl_product.enddate')
                    ->andWhere('tbl_product.isPromote = :isPromote')
                    ->andWhere('tbl_product.promoType = 1')
                    ->setParameter('dateTime', $date)
                    ->setParameter('isPromote', EsProduct::PRODUCT_IS_PROMOTE_ON)
                    ->getQuery();
        $product = $query->getOneOrNullResult();

        return $product;
    }

    /**
     *TODO:
     *  Define variable Date and Time or Rounds
     *  function getSchoolWithStudentsByRound
     *  function vote
     *NOTE:
     * User can only vote one student / round
     * In-School :
     *  Round 1 (Feb 23 - March 6)
     *  Round 2 (March 7 - April 7)
     *  If number of participants is <= 3, no need for round 2.
     *  Else, round 2.
     * Inter-School :
     *  Final Round (April 8 - June 8)
     * -->This should not be pushed to Github project
     */

    /**
     * Promo : Estudyantrepreneur
     * Get School and its student by date / round
     * @param $round
     * @return array
     */
    public function getSchoolWithStudentsByRoundForEstudyantrepreneur($round)
    {

    }

    /**
     * Promo : Estudyantrepreneur
     * Vote a student
     * @param $studentEntity
     * @param $schoolEntity
     * @param $memberEntity
     * @return bool
     */
    public function voteForEstudyantrepreneur($studentEntity, $schoolEntity, $memberEntity)
    {
        return true;
    }
}
