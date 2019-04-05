<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405141920 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE alert ADD src_ip LONGTEXT DEFAULT NULL, ADD src_port INT DEFAULT NULL, ADD dest_ip LONGTEXT DEFAULT NULL, ADD dest_port INT DEFAULT NULL, ADD proto LONGTEXT DEFAULT NULL, ADD action LONGTEXT DEFAULT NULL, ADD signature_id INT DEFAULT NULL, ADD rev INT DEFAULT NULL, ADD signature LONGTEXT DEFAULT NULL, ADD category LONGTEXT DEFAULT NULL, ADD severity INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE alert DROP src_ip, DROP src_port, DROP dest_ip, DROP dest_port, DROP proto, DROP action, DROP signature_id, DROP rev, DROP signature, DROP category, DROP severity');
    }
}
