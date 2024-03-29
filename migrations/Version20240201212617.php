<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201212617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_weather (product_id INT NOT NULL, weather_id INT NOT NULL, INDEX IDX_9D09C8DE4584665A (product_id), INDEX IDX_9D09C8DE8CE675E (weather_id), PRIMARY KEY(product_id, weather_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weather (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_weather ADD CONSTRAINT FK_9D09C8DE4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_weather ADD CONSTRAINT FK_9D09C8DE8CE675E FOREIGN KEY (weather_id) REFERENCES weather (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_weather DROP FOREIGN KEY FK_9D09C8DE4584665A');
        $this->addSql('ALTER TABLE product_weather DROP FOREIGN KEY FK_9D09C8DE8CE675E');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_weather');
        $this->addSql('DROP TABLE weather');
    }
}
