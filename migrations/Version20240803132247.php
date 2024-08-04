<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803132247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iprangelocation ADD ip_byte_1_from SMALLINT NOT NULL, ADD ip_byte_1_to SMALLINT NOT NULL, ADD ip_byte_2_from SMALLINT NOT NULL, ADD ip_byte_2_to SMALLINT NOT NULL, ADD ip_byte_3_from SMALLINT NOT NULL, ADD ip_byte_3_to SMALLINT NOT NULL, ADD ip_byte_4_from SMALLINT NOT NULL, ADD ip_byte_4_to SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE iprangelocation DROP ip_byte_1_from, DROP ip_byte_1_to, DROP ip_byte_2_from, DROP ip_byte_2_to, DROP ip_byte_3_from, DROP ip_byte_3_to, DROP ip_byte_4_from, DROP ip_byte_4_to');
    }
}
