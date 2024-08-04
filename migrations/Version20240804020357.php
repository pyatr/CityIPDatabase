<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240804020357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iprangelocation CHANGE ip_byte_1_from ip_byte_1_from TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\', CHANGE ip_byte_1_to ip_byte_1_to TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\', CHANGE ip_byte_2_from ip_byte_2_from TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\', CHANGE ip_byte_2_to ip_byte_2_to TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\', CHANGE ip_byte_3_from ip_byte_3_from TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\', CHANGE ip_byte_3_to ip_byte_3_to TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\', CHANGE ip_byte_4_from ip_byte_4_from TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\', CHANGE ip_byte_4_to ip_byte_4_to TINYINT UNSIGNED NOT NULL COMMENT \'(DC2Type:tinyint)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iprangelocation CHANGE ip_byte_1_from ip_byte_1_from SMALLINT NOT NULL, CHANGE ip_byte_1_to ip_byte_1_to SMALLINT NOT NULL, CHANGE ip_byte_2_from ip_byte_2_from SMALLINT NOT NULL, CHANGE ip_byte_2_to ip_byte_2_to SMALLINT NOT NULL, CHANGE ip_byte_3_from ip_byte_3_from SMALLINT NOT NULL, CHANGE ip_byte_3_to ip_byte_3_to SMALLINT NOT NULL, CHANGE ip_byte_4_from ip_byte_4_from SMALLINT NOT NULL, CHANGE ip_byte_4_to ip_byte_4_to SMALLINT NOT NULL');
    }
}
