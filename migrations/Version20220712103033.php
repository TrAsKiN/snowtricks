<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712103033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trick_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, type VARCHAR(255) NOT NULL, file VARCHAR(255) DEFAULT NULL, youtube VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media_trick (media_id INT NOT NULL, trick_id INT NOT NULL, PRIMARY KEY(media_id, trick_id))');
        $this->addSql('CREATE INDEX IDX_C7E96DAFEA9FDD75 ON media_trick (media_id)');
        $this->addSql('CREATE INDEX IDX_C7E96DAFB281BE2E ON media_trick (trick_id)');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, author_id INT NOT NULL, trick_id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307FF675F31B ON message (author_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FB281BE2E ON message (trick_id)');
        $this->addSql('COMMENT ON COLUMN message.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE trick (id INT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8F0A91EF675F31B ON trick (author_id)');
        $this->addSql('CREATE TABLE trick_tag (trick_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(trick_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_B4395361B281BE2E ON trick_tag (trick_id)');
        $this->addSql('CREATE INDEX IDX_B4395361BAD26311 ON trick_tag (tag_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE media_trick ADD CONSTRAINT FK_C7E96DAFEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_trick ADD CONSTRAINT FK_C7E96DAFB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trick_tag ADD CONSTRAINT FK_B4395361B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trick_tag ADD CONSTRAINT FK_B4395361BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media_trick DROP CONSTRAINT FK_C7E96DAFEA9FDD75');
        $this->addSql('ALTER TABLE trick_tag DROP CONSTRAINT FK_B4395361BAD26311');
        $this->addSql('ALTER TABLE media_trick DROP CONSTRAINT FK_C7E96DAFB281BE2E');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FB281BE2E');
        $this->addSql('ALTER TABLE trick_tag DROP CONSTRAINT FK_B4395361B281BE2E');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE trick DROP CONSTRAINT FK_D8F0A91EF675F31B');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trick_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_trick');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE trick');
        $this->addSql('DROP TABLE trick_tag');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
