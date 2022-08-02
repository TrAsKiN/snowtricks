<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728051406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick ALTER created_at SET DEFAULT \'now\'');
        $this->addSql('ALTER TABLE trick ALTER updated_at SET DEFAULT \'now\'');
        $this->addSql('ALTER TABLE "user" ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trick ALTER created_at SET DEFAULT \'2022-07-28 05:12:38.347962\'');
        $this->addSql('ALTER TABLE trick ALTER updated_at SET DEFAULT \'2022-07-28 05:12:38.347962\'');
        $this->addSql('ALTER TABLE "user" DROP image');
    }
}