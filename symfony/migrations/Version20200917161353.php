<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200917161353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE car_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE car_brand_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE car_model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql('CREATE TABLE car (id INT NOT NULL, city_id INT NOT NULL, brand_id INT NOT NULL, brand_model_id INT NOT NULL, title VARCHAR(200) NOT NULL, description VARCHAR(2500) DEFAULT NULL, prod_year SMALLINT NOT NULL, body_type SMALLINT NOT NULL, seats SMALLINT DEFAULT NULL, fuel SMALLINT DEFAULT NULL, engine_capacity SMALLINT DEFAULT NULL, gearbox_type SMALLINT DEFAULT NULL, wheel_drive SMALLINT DEFAULT NULL, odometer INT DEFAULT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69D3CCE3900 ON car (city_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69D9C3A7E96 ON car (brand_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69D4107BEA0 ON car (brand_model_id)');

        $this->addSql('CREATE TABLE car_brand (id INT NOT NULL, title VARCHAR(50) NOT NULL, PRIMARY KEY(id))');

        $this->addSql('CREATE TABLE car_brand_model (id INT NOT NULL, title VARCHAR(50) NOT NULL, car_brand_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D1A662C2F64382E3 ON car_brand_model (car_brand_id)');

        $this->addSql('CREATE TABLE city (id INT NOT NULL, title VARCHAR(50) NOT NULL, PRIMARY KEY(id))');

        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D3CCE3900 FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D9C3A7E96 FOREIGN KEY (brand_id) REFERENCES car_brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D4107BEA0 FOREIGN KEY (brand_model_id) REFERENCES car_brand_model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE car_brand_model ADD CONSTRAINT FK_D1A662C2F64382E3 FOREIGN KEY (car_brand_id) REFERENCES car_brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D9C3A7E96');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D4107BEA0');
        $this->addSql('ALTER TABLE car_brand DROP CONSTRAINT FK_D1A662C2F64382E3');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D3CCE3900');
        $this->addSql('DROP SEQUENCE car_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE car_brand_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE car_model_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_brand');
        $this->addSql('DROP TABLE car_brand_model');
        $this->addSql('DROP TABLE city');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "user"');
    }
}
