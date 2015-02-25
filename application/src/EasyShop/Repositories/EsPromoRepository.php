<?php
namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsPromo;
use EasyShop\Entities\EsPromoType;

class EsPromoRepository extends EntityRepository
{

    /**
     * Promo : BuyAtZero
     * Register member for buy at zero promo
     * @param $productId
     * @param $memberId
     * @return bool
     */
    public function registerMemberForBuyAtZero($productId, $memberId)
    {
        $isAccountRegistered = $this->_em->getRepository('EasyShop\Entities\EsPromo')
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

            $this->_em->persist($promo);
            $this->_em->flush();
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
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('tblProduct.idProduct, tblPromo.memberId AS c_member_id, tblProductImage.productImagePath as path')
                    ->from('EasyShop\Entities\EsPromo', 'tblPromo')
                    ->leftJoin('EasyShop\Entities\EsProduct', 'tblProduct', 'WITH', 'tblProduct.idProduct = tblPromo.productId')
                    ->leftJoin('EasyShop\Entities\EsProductImage', 'tblProductImage', 'WITH', 'tblProductImage.product = tblProduct.idProduct')
                    ->where('tblPromo.code = :code AND tblPromo.promoType = :promoType')
                    ->setParameter('code', $code)
                    ->setParameter('promoType', EsPromoType::SCRATCH_AND_WIN)
                    ->getQuery();
        $result = $query->getResult();

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
        $promo = $this->_em->getRepository('EasyShop\Entities\EsPromo')->findOneBy(['code' => $code]);
        $promo->setMemberId($memberId);
        $this->_em->persist($promo);
        $this->_em->flush();

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
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('tbl_product')
                    ->from('EasyShop\Entities\EsProduct', 'tbl_product')
                    ->where(':dateTime >= tbl_product.startdate')
                    ->andWhere(':dateTime <= tbl_product.enddate')
                    ->andWhere('tbl_product.isPromote = :isPromote')
                    ->andWhere('tbl_product.promoType = 1')
                    ->setParameter('dateTime', $date)
                    ->setParameter('isPromote', EsProduct::PRODUCT_IS_PROMOTE_ON)
                    ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Get total vote per school by date
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getTotalVotesByDate($startDate, $endDate)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('schoolTbl.name, count(promoTbl.memberId) as vote')
                    ->from('EasyShop\Entities\EsPromo', 'promoTbl')
                    ->leftJoin('EasyShop\Entities\EsStudent', 'studentTbl', 'WITH', 'studentTbl.idStudent = promoTbl.studentId')
                    ->leftJoin('EasyShop\Entities\EsSchool', 'schoolTbl', 'WITH', 'schoolTbl.idSchool = studentTbl.school')
                    ->where('promoTbl.createdAt >= :startDate')
                    ->andWhere('promoTbl.createdAt < :endDate')
                    ->groupBy('studentTbl.school')
                    ->setParameter('startDate', $startDate)
                    ->setParameter('endDate', $endDate)
                    ->getQuery();

        return $query->getResult();
    }

}
