<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112140646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merci (id INT AUTO_INCREMENT NOT NULL, from_employee_id INT NOT NULL, to_employee_id INT NOT NULL, message LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_86B31E76124CC039 (from_employee_id), INDEX IDX_86B31E76A290CF3F (to_employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE merci ADD CONSTRAINT FK_86B31E76124CC039 FOREIGN KEY (from_employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE merci ADD CONSTRAINT FK_86B31E76A290CF3F FOREIGN KEY (to_employee_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merci DROP FOREIGN KEY FK_86B31E76124CC039');
        $this->addSql('ALTER TABLE merci DROP FOREIGN KEY FK_86B31E76A290CF3F');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE merci');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
