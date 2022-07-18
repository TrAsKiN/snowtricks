<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220718185557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE media_trick');
        $this->addSql('ALTER TABLE media ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6A2CA10CB281BE2E ON media (trick_id)');
        $this->addSql('ALTER TABLE trick ALTER created_at SET DEFAULT \'now\'');
        $this->addSql('ALTER TABLE trick ALTER updated_at SET DEFAULT \'now\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE media_trick (media_id INT NOT NULL, trick_id INT NOT NULL, PRIMARY KEY(media_id, trick_id))');
        $this->addSql('CREATE INDEX idx_c7e96dafb281be2e ON media_trick (trick_id)');
        $this->addSql('CREATE INDEX idx_c7e96dafea9fdd75 ON media_trick (media_id)');
        $this->addSql('ALTER TABLE media_trick ADD CONSTRAINT fk_c7e96dafea9fdd75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_trick ADD CONSTRAINT fk_c7e96dafb281be2e FOREIGN KEY (trick_id) REFERENCES trick (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10CB281BE2E');
        $this->addSql('DROP INDEX IDX_6A2CA10CB281BE2E');
        $this->addSql('ALTER TABLE media DROP trick_id');
        $this->addSql('ALTER TABLE trick ALTER created_at SET DEFAULT \'2022-07-18 11:42:30.497154\'');
        $this->addSql('ALTER TABLE trick ALTER updated_at SET DEFAULT \'2022-07-18 11:42:30.497154\'');
    }
}
