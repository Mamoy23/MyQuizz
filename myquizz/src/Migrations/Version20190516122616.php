<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516122616 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reponse ADD question_id INT NOT NULL');
        // $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        // $this->addSql('CREATE INDEX IDX_5FB6DEC71E27F6BF ON reponse (question_id)');
        // $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        // $this->addSql('CREATE INDEX IDX_7C77973DBCF5E72D ON quizz (categorie_id)');
        // $this->addSql('ALTER TABLE question ADD id_quizz INT DEFAULT NULL');
        // $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
        // $this->addSql('CREATE INDEX IDX_B6F7494EBA934BCD ON question (quizz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBA934BCD');
        // $this->addSql('DROP INDEX IDX_B6F7494EBA934BCD ON question');
        // $this->addSql('ALTER TABLE question DROP id_quizz');
        // $this->addSql('ALTER TABLE quizz DROP FOREIGN KEY FK_7C77973DBCF5E72D');
        // $this->addSql('DROP INDEX IDX_7C77973DBCF5E72D ON quizz');
        // $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        // $this->addSql('DROP INDEX IDX_5FB6DEC71E27F6BF ON reponse');
        $this->addSql('ALTER TABLE reponse DROP question_id');
    }
}