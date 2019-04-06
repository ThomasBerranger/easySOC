<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190406091855 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE alert CHANGE src_ip src_ip VARCHAR(255) DEFAULT NULL, CHANGE dest_ip dest_ip VARCHAR(255) DEFAULT NULL, CHANGE proto proto VARCHAR(255) DEFAULT NULL, CHANGE action action VARCHAR(255) DEFAULT NULL, CHANGE signature signature VARCHAR(255) DEFAULT NULL, CHANGE category category VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE alert CHANGE src_ip src_ip LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE dest_ip dest_ip LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE proto proto LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE action action LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE signature signature LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE category category LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
