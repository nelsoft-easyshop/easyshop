<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150212163547 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            UPDATE `es_ban_type` SET `title` = 'Inquiry Non-compliance', `message` = 'This account has been suspended due to non-compliance from our repeated inquiries. Please contact our Customer Service Support to reactivate this account: Monday - Friday 9:00 AM to 6:00 PM (02) 354-5973 Local 103. '
            WHERE id_ban_type = 2
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            UPDATE `es_ban_type`SET `title` = 'Excessive Refunds', `message` = 'This account has been temporarily suspended due to multiple refunded trasactions. For more details and clarifications contact us at info@easyshop.ph.'
            WHERE id_ban_type = 2
        ");
    }
}
