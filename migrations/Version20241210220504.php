<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210220504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adverts (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, location VARCHAR(255) DEFAULT NULL, INDEX IDX_8C88E777A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adverts_adverts (adverts_source INT NOT NULL, adverts_target INT NOT NULL, INDEX IDX_1EA7CD3CA22EAFBF (adverts_source), INDEX IDX_1EA7CD3CBBCBFF30 (adverts_target), PRIMARY KEY(adverts_source, adverts_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E777A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE adverts_adverts ADD CONSTRAINT FK_1EA7CD3CA22EAFBF FOREIGN KEY (adverts_source) REFERENCES adverts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adverts_adverts ADD CONSTRAINT FK_1EA7CD3CBBCBFF30 FOREIGN KEY (adverts_target) REFERENCES adverts (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adverts DROP FOREIGN KEY FK_8C88E777A76ED395');
        $this->addSql('ALTER TABLE adverts_adverts DROP FOREIGN KEY FK_1EA7CD3CA22EAFBF');
        $this->addSql('ALTER TABLE adverts_adverts DROP FOREIGN KEY FK_1EA7CD3CBBCBFF30');
        $this->addSql('DROP TABLE adverts');
        $this->addSql('DROP TABLE adverts_adverts');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE users');
    }
}
