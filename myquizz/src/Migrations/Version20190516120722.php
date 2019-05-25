<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516120722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quizz ADD categorie_id INT NOT NULL');
        //$this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        //$this->addSql('CREATE INDEX IDX_7C77973DBCF5E72D ON quizz (categorie_id)');
        $this->addSql('ALTER TABLE users CHANGE role role VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('ALTER TABLE quizz DROP FOREIGN KEY FK_7C77973DBCF5E72D');
        //$this->addSql('DROP INDEX IDX_7C77973DBCF5E72D ON quizz');
        $this->addSql('ALTER TABLE quizz DROP categorie_id');
        $this->addSql('ALTER TABLE users CHANGE role role VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci');
    }
}
