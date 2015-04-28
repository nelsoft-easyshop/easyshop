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
              COALESCE(NULLIF(c.store_name, ''), c.username) AS recipient,
              a.from_id,
              COALESCE(NULLIF(c.imgurl, ''), 'assets/user/default') AS recipient_img,
              COALESCE(NULLIF(b.store_name, ''), b.username) AS sender,
              COALESCE(NULLIF(b.imgurl, ''), 'assets/user/default') AS sender_img,
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
     * @param integer[] $messageId
     * @param integer $memberId
     * @return integer
     */
    public function delete($messageIds, $memberId)
    {
        $statusMessageNotDeleted = EsMessages::MESSAGE_NOT_DELETED;
        $statusMessageDeletedByReceiver = EsMessages::MESSAGE_DELETED_BY_RECEIVER;
        $statusMessageDeletedBySender = EsMessages::MESSAGE_DELETED_BY_SENDER;
        $statusMessageDeletedByBoth = EsMessages::MESSAGE_DELETED_BY_BOTH;

        $em = $this->_em;
        $query = 
           "UPDATE 
                `es_messages`
            SET `is_delete` = 
                CASE
                    WHEN `is_delete` = ? AND `from_id` = ? THEN ?
                    WHEN `is_delete` = ? AND `to_id` = ? THEN ?
                    WHEN `is_delete` = ? AND `from_id` = ? THEN ?
                    WHEN `is_delete` = ? AND `to_id` = ? THEN ?
                    ELSE `is_delete`
                END
            WHERE 
                (`from_id` = ? or `to_id` = ?) AND `id_msg` IN(?)";

        $count = $em->getConnection()
                    ->executeUpdate(
                        $query,[
                            $statusMessageNotDeleted,
                            $memberId,
                            $statusMessageDeletedBySender,
                            $statusMessageNotDeleted,
                            $memberId,
                            $statusMessageDeletedByReceiver,
                            $statusMessageDeletedByReceiver,
                            $memberId,
                            $statusMessageDeletedByBoth,
                            $statusMessageDeletedBySender,
                            $memberId,
                            $statusMessageDeletedByBoth,
                            $memberId,
                            $memberId,
                            $messageIds,
                        ],[
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_STR,
                            \PDO::PARAM_INT,
                            \PDO::PARAM_INT,
                            \Doctrine\DBAL\Connection::PARAM_INT_ARRAY,
                        ]);
              
        return (int) $count;
    }

    /**
     * Update message/s to seen
     * @param $memberId
     * @param $messageId array
     * @return integer Number of updated message
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

        return (int) $count;
    }

    /**
     * Retrieves the conversation headers
     * 
     * @param integer $memberId
     * @param integer $offset
     * @param integer $limit
     * @param string $searchString
     * @return mixed
     */
    public function getConversationHeaders($memberId, $offset = 0, $limit = PHP_INT_MAX, $searchString = NULL)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_msg', 'id_msg');
        $rsm->addScalarResult('to_id', 'to_id');
        $rsm->addScalarResult('from_id', 'from_id');
        $rsm->addScalarResult('unread_message_count', 'unread_message_count'); 
        $rsm->addScalarResult('is_sender', 'is_sender');
        $rsm->addScalarResult('last_message', 'last_message');
        $rsm->addScalarResult('last_date', 'last_date');
        $rsm->addScalarResult('partner_storename', 'partner_storename');
        $rsm->addScalarResult('partner_member_id', 'partner_member_id');

        $searchCondition = "";
        if($searchString !== NULL){
            $searchCondition = " WHERE  
                CASE
                    WHEN partner.store_name != '' THEN COALESCE(partner.store_name, partner.username)
                    ELSE partner.username
                END LIKE :searchString";
        }
        
        $sql = "SELECT
                   messages.id_msg,
                   messages.to_id,
                   messages.from_id,
                   SUM(IF(messages.opened = :messageOpened, 0, 1)) as unread_message_count,
                   IF(to_id = :memberId, 0, 1) as is_sender,
                   partner.id_member as partner_member_id,
                   IF(partner.store_name != '', COALESCE(partner.store_name, partner.username), partner.username) as partner_storename,
                   messages.message as last_message,
                   messages.time_sent as last_date
               FROM 
                   (SELECT
                        * 
                    FROM
                        es_messages 
                    WHERE 
                        (es_messages.to_id = :memberId or es_messages.from_id = :memberId ) AND
                        (es_messages.is_delete = :notDeleted OR
                         es_messages.is_delete = 
                            CASE
                                WHEN to_id = :memberId THEN :deletedBySender
                                ELSE :deletedByReceiver
                            END
                        )
                    ORDER BY 
                        es_messages.time_sent DESC
                    ) as messages
               INNER JOIN
                   es_member partner ON 
                     CASE
                         WHEN to_id = :memberId THEN messages.from_id = partner.id_member
                         ELSE messages.to_id = partner.id_member   
                     END ".$searchCondition."
               GROUP BY
                   partner.id_member
               ORDER BY
                   messages.time_sent DESC
               LIMIT
                  :offset, :limit
               ";

        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('memberId', $memberId);
        $query->setParameter('messageOpened', EsMessages::MESSAGE_READ );
        $query->setParameter('offset', $offset);
        $query->setParameter('limit', $limit);
        $query->setParameter('notDeleted', EsMessages::MESSAGE_NOT_DELETED);
        $query->setParameter('deletedBySender', EsMessages::MESSAGE_DELETED_BY_SENDER);
        $query->setParameter('deletedByReceiver', EsMessages::MESSAGE_DELETED_BY_RECEIVER);
        if($searchString !== NULL){
             $query->setParameter('searchString', '%'.$searchString.'%');
        }

        return $query->getResult();
    }

    /**
     * Get messages between two users
     *
     * @param integer $memberId
     * @param integer $partnerId
     * @param integer $offset
     * @param integer $limit
     * @return mixed
     */
    public function getConversationMessages($memberId, $partnerId, $offset = 0, $limit = PHP_INT_MAX)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_msg', 'id_msg');
        $rsm->addScalarResult('message', 'message');
        $rsm->addScalarResult('time_sent', 'time_sent');
        $rsm->addScalarResult('sender_member_id', 'sender_member_id');
        $rsm->addScalarResult('is_sender', 'is_sender');

        $sql = "    
            SELECT
                id_msg,
                message,
                time_sent,
                sender.id_member as sender_member_id,
                IF(to_id = :memberId, 0, 1) as is_sender
            FROM
                es_messages 
            LEFT JOIN
                es_member as sender ON sender.id_member = es_messages.from_id
            WHERE 
                (
                     (es_messages.to_id = :memberId AND es_messages.from_id = :partnerId ) OR
                     (es_messages.to_id = :partnerId AND es_messages.from_id = :memberId)
                ) AND (
                     es_messages.is_delete = :notDeleted OR
                     es_messages.is_delete = 
                          CASE
                              WHEN to_id = :memberId THEN :deletedBySender
                              ELSE :deletedByReceiver
                          END
               )
            ORDER BY 
                es_messages.time_sent DESC
            LIMIT
                :offset, :limit
        ";
        
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('memberId', $memberId);
        $query->setParameter('partnerId', $partnerId );
        $query->setParameter('offset', $offset);
        $query->setParameter('limit', $limit);
        $query->setParameter('notDeleted', EsMessages::MESSAGE_NOT_DELETED);
        $query->setParameter('deletedBySender', EsMessages::MESSAGE_DELETED_BY_SENDER);
        $query->setParameter('deletedByReceiver', EsMessages::MESSAGE_DELETED_BY_RECEIVER);

        return $query->getResult();
    }

}
