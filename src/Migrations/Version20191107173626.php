<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107173626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cours ADD fk_intervenant_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CA5846A6D FOREIGN KEY (fk_intervenant_id_id) REFERENCES intervenant (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CA5846A6D ON cours (fk_intervenant_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CA5846A6D');
        $this->addSql('DROP INDEX IDX_FDCA8C9CA5846A6D ON cours');
        $this->addSql('ALTER TABLE cours DROP fk_intervenant_id_id');
    }
}
