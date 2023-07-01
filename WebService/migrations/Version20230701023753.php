<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230701023753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona a coluna user_name na tabela user_authentication';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_authentication ADD user_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_authentication ALTER user_id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_authentication DROP user_name');
        $this->addSql('CREATE SEQUENCE user_authentication_user_id_seq');
        $this->addSql('SELECT setval(\'user_authentication_user_id_seq\', (SELECT MAX(user_id) FROM user_authentication))');
        $this->addSql('ALTER TABLE user_authentication ALTER user_id SET DEFAULT nextval(\'user_authentication_user_id_seq\')');
    }
}
