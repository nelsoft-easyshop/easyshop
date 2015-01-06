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

    /**
     * Get all message
     * @param $memberId
     * @return array
     */
    public function getAllMessage($memberId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_msg', 'id_msg');
        $rsm->addScalarResult('to_id', 'to_id');
        $rsm->addScalarResult('recipient', 'recipient');
        $rsm->addScalarResult('from_id', 'from_id');
        $rsm->addScalarResult('recipient_img', 'recipient_img');
        $rsm->addScalarResult('sender', 'sender');
        $rsm->addScalarResult('sender_img', 'sender_img');
        $rsm->addScalarResult('message', 'message');
        $rsm->addScalarResult('time_sent', 'time_sent');
        $rsm->addScalarResult('opened', 'opened');
        $rsm->addScalarResult('is_delete', 'is_delete');
        $sql = "
            SELECT
              a.id_msg,
              a.to_id,
              c.username AS recipient,
              a.from_id,
              CASE WHEN c.imgurl = '' THEN 'assets/user/default' ELSE c.imgurl END AS recipient_img,
              b.username AS sender,
              CASE WHEN b.imgurl = '' THEN 'assets/user/default' ELSE b.imgurl END AS sender_img,
              a.message,
              a.time_sent,
              a.opened,
              a.is_delete
            FROM
              es_messages AS a
              LEFT JOIN es_member AS b
                ON a.from_id = b.id_member
              LEFT JOIN es_member AS c
                ON a.to_id = c.id_member
            WHERE (a.to_id = :memberId
                OR a.from_id = :memberId) AND is_delete != :deletedBoth

            ORDER BY time_sent DESC";

        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('memberId', $memberId);
        $query->setParameter('deletedBoth', EsMessages::MESSAGE_DELETED_BY_BOTH);

        return $query->getResult();
    }

    /**
     * Soft deletes message/conversation
     * @param $messageId
     * @param $memberId array
     * @return int
     */
    public function delete($messageId, $memberId)
    {
        $em = $this->_em;
        $query = "UPDATE `es_messages`
            SET `is_delete` = CASE
                    WHEN `is_delete` = '0' THEN `is_delete` + (CASE WHEN `from_id` = ? THEN 2 ELSE 1 END)
                    WHEN `is_delete` = '1' THEN `is_delete` + 2
                    WHEN `is_delete` = '2' THEN `is_delete` + 1
                    ELSE 3
                   END
            WHERE `id_msg` IN(?)";
        $count = $em->getConnection()->executeUpdate($query, [$memberId, $messageId], [\PDO::PARAM_INT, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY]);

        return $count;
    }

    /**
     * Update message/s to seen
     * @param $memberId
     * @param $messageId array
     * @return boolean
     */
    public function updateToSeen($memberId, $messageId)
    {
        $em = $this->_em;
        $query = "
                UPDATE `es_messages`
			    SET `opened` = ?
			    WHERE `to_id` = ? AND `id_msg` IN(?)";
        $count = $em->getConnection()->executeUpdate($query,
            [
                EsMessages::MESSAGE_READ,
                $memberId,
                $messageId,
            ],
            [
                \PDO::PARAM_INT,
                \PDO::PARAM_INT,
                \Doctrine\DBAL\Connection::PARAM_INT_ARRAY
            ]);

        return (bool) $count;
    }

}
