<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201227161645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE user_a_seq CASCADE');
        $this->addSql('ALTER TABLE "user" ADD status SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD email_confirm_key VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE user_a_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE "user" DROP status');
        $this->addSql('ALTER TABLE "user" DROP email_confirm_key');
        $this->addSql('CREATE SEQUENCE user_id_seq');
        $this->addSql('SELECT setval(\'user_id_seq\', (SELECT MAX(id) FROM "user"))');
        $this->addSql('ALTER TABLE "user" ALTER id SET DEFAULT nextval(\'user_id_seq\')');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER updated_at DROP NOT NULL');
    }
}
