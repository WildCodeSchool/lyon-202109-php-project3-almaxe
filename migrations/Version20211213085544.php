<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213085544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, affiliate_key VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, picture VARCHAR(255) NOT NULL, blacklist VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blacklist (id INT AUTO_INCREMENT NOT NULL, partner_id INT NOT NULL, name VARCHAR(255) NOT NULL, last_tested DATE NOT NULL, partner_product_id VARCHAR(255) NOT NULL, INDEX IDX_3B1753859393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, partner_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, price_currency VARCHAR(255) NOT NULL, height DOUBLE PRECISION NOT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, picture VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, partner_product_id VARCHAR(255) NOT NULL, INDEX IDX_D34A04AD9393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blacklist ADD CONSTRAINT FK_3B1753859393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blacklist DROP FOREIGN KEY FK_3B1753859393F8FE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9393F8FE');
        $this->addSql('DROP TABLE blacklist');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE product');
    }
}
