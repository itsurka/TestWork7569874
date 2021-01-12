<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200927050629 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE car_model_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE car_brand_model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER INDEX uniq_773de69d3cce3900 RENAME TO UNIQ_773DE69D8BAC62AF');
        $this->addSql('ALTER INDEX uniq_773de69d9c3a7e96 RENAME TO UNIQ_773DE69D44F5D008');
        $this->addSql('ALTER INDEX uniq_773de69d4107bea0 RENAME TO UNIQ_773DE69DBE4524F6');
        $this->addSql('ALTER TABLE car_brand_model DROP CONSTRAINT fk_d1a662c2f64382e3');
        $this->addSql('DROP INDEX idx_d1a662c2f64382e3');
        $this->addSql('ALTER TABLE car_brand_model DROP car_brand_id');
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE car_brand_model_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE car_model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER INDEX uniq_773de69d44f5d008 RENAME TO uniq_773de69d9c3a7e96');
        $this->addSql('ALTER INDEX uniq_773de69d8bac62af RENAME TO uniq_773de69d3cce3900');
        $this->addSql('ALTER INDEX uniq_773de69dbe4524f6 RENAME TO uniq_773de69d4107bea0');
        $this->addSql('ALTER TABLE car_brand_model ADD car_brand_id INT NOT NULL');
        $this->addSql('ALTER TABLE car_brand_model ADD CONSTRAINT fk_d1a662c2f64382e3 FOREIGN KEY (car_brand_id) REFERENCES car_brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d1a662c2f64382e3 ON car_brand_model (car_brand_id)');
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE "user" DROP updated_at');
    }
}
