<?php
namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;

class EsProductExternalLinkRepository extends EntityRepository
{
    /**
     * Get external links by product id
     * @param $productId
     * @return array
     */
    public function getExternalLinksByProductId($productId)
    {
        $productExternalLinks = [];
        $productExternalLinksEntity = $this->_em->getRepository('EasyShop\Entities\EsProductExternalLink')
                                                ->findBy([
                                                    'productId' => $productId
                                                ]);

        if ($productExternalLinksEntity) {
            foreach ($productExternalLinksEntity as $link) {
                $productExternalLinks[$link->getSocialMediaProviderId()] = $link;
            }
        }

        return $productExternalLinks;
    }
}
