<?php

namespace EasyShop\Repositories;

use EasyShop\Entities\EsMessages as EsMessages;
use Doctrine\ORM\EntityRepository; 
use Doctrine\ORM\Query\ResultSetMapping;

class EsMessagesRepository extends EntityRepository
{
    /**
     * Retrieves the number of unread messages
     *
     * @param integer $memberId
     * @return integer
     */
    public function getUnreadMessageCount($memberId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('count', 'count');

        $sql = "SELECT 
                    count(A.id_msg) as count 
                FROM (
                    SELECT 
                        id_msg
                    FROM
                        es_messages
                    WHERE
                        to_id = :memberId AND
                        opened = :isOpened AND
                        from_id != :memberId AND
                        is_delete NOT IN (:deletedBoth, :deletedRecipient)
                    GROUP BY from_id
                ) A";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('memberId', $memberId);
        $query->setParameter('isOpened', EsMessages::MESSAGE_UNREAD);
        $query->setParameter('deletedBoth', EsMessages::MESSAGE_DELETED_BY_BOTH);
        $query->setParameter('deletedRecipient', EsMessages::MESSAGE_DELETED_BY_RECEIVER);

        $count = $query->getResult()[0]['count'];
        return $count;
    }
    
}