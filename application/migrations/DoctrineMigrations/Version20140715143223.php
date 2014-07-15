<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140715143223 extends AbstractMigration
{
    public function up(Schema $schema)
    {
	$this->addSql("
		CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_expiredDragonpayTransaction`(
			    IN i_txnid VARCHAR (1024)
			)
		BEGIN
			DECLARE o_message VARCHAR(100);
			DECLARE o_success BOOLEAN;

			DECLARE v_product_item_id INT(10);
			DECLARE v_order_qty INT(4);
			DECLARE v_product_qty INT(10);
			DECLARE v_order_product_id INT(10);
			DECLARE v_order_id INT(10);
			DECLARE loopcount INT DEFAULT 0;
			DECLARE done INT DEFAULT 0;

			DECLARE cur CURSOR FOR
				SELECT eop.product_item_id, eop.order_quantity, epi.quantity, eop.id_order_product, eo.id_order
				FROM es_order eo
				INNER JOIN es_order_product eop
					ON eo.id_order = eop.order_id AND eo.transaction_id = i_txnid
				INNER JOIN es_product_item epi
					ON eop.product_item_id = epi.id_product_item
				WHERE eo.payment_method_id = 2 AND eo.order_status = 99;
	
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

			DECLARE EXIT HANDLER FOR SQLEXCEPTION
			  BEGIN
			      ROLLBACK;
			
			   SELECT o_success AS o_success, 'Error Handler 1' AS o_message;
			  END;

			SET o_message = 'Database error. Failed to fetch transaction data.';
			SET o_success = FALSE;

			OPEN cur;
			read_loop: LOOP
		
				FETCH cur INTO v_product_item_id, v_order_qty, v_product_qty, v_order_product_id, v_order_id;
				IF done THEN
					IF loopcount > 0 THEN

				
						UPDATE es_order
						SET order_status = 2, datemodified = NOW()
						WHERE transaction_id = i_txnid AND payment_method_id = 2 AND order_status = 99 AND id_order = v_order_id;

				
						INSERT INTO es_order_history (order_id, `comment`, `date_added`, order_status)
						VALUES (v_order_id, 'DRAGONPAY EXPIRED', NOW(), 2);

						SET o_message = 'Database updated.';
						SET o_success = TRUE;

					ELSE
						SET o_message = 'Database error. Failed to update order and order_product.';
						SET o_success = FALSE;

					END IF;

					LEAVE read_loop;

				END IF;

				SET loopcount = loopcount + 1;

		
				UPDATE es_product_item
				SET quantity = v_order_qty + v_product_qty
				WHERE id_product_item = v_product_item_id;

		
				UPDATE es_order_product
				SET status = 6
				WHERE id_order_product = v_order_product_id;

		
		
				INSERT INTO es_order_product_history (order_product_id, `comment`, `date_added`, order_product_status)
				VALUES(v_order_product_id, 'DRAGONPAY EXPIRED', NOW(), '6');

			END LOOP;
			CLOSE cur;

			SELECT o_message, o_success, loopcount;

		END
	"); 

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
	$this->addSql("DROP PROCEDURE `es_sp_expiredDragonpayTransaction`");

    }


	

}
