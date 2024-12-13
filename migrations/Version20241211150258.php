<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211150258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adverts_categories (adverts_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_8286D5AAC5A3D550 (adverts_id), INDEX IDX_8286D5AAA21214B7 (categories_id), PRIMARY KEY(adverts_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adverts_categories ADD CONSTRAINT FK_8286D5AAC5A3D550 FOREIGN KEY (adverts_id) REFERENCES adverts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adverts_categories ADD CONSTRAINT FK_8286D5AAA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adverts_categories DROP FOREIGN KEY FK_8286D5AAC5A3D550');
        $this->addSql('ALTER TABLE adverts_categories DROP FOREIGN KEY FK_8286D5AAA21214B7');
        $this->addSql('DROP TABLE adverts_categories');
    }
}
