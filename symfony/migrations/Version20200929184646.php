<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929184646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_773de69d44f5d008');
        $this->addSql('DROP INDEX uniq_773de69dbe4524f6');
        $this->addSql('CREATE INDEX IDX_773DE69D44F5D008 ON car (brand_id)');
        $this->addSql('CREATE INDEX IDX_773DE69DBE4524F6 ON car (brand_model_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_773DE69D44F5D008');
        $this->addSql('DROP INDEX IDX_773DE69DBE4524F6');
        $this->addSql('CREATE UNIQUE INDEX uniq_773de69d44f5d008 ON car (brand_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_773de69dbe4524f6 ON car (brand_model_id)');
    }
}
