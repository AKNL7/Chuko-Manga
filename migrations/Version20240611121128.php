<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611121128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP INDEX UNIQ_6D28840D4B89032C, ADD INDEX IDX_6D28840D4B89032C (post_id)');
        $this->addSql('ALTER TABLE payment DROP INDEX UNIQ_6D28840DA76ED395, ADD INDEX IDX_6D28840DA76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP INDEX IDX_6D28840D4B89032C, ADD UNIQUE INDEX UNIQ_6D28840D4B89032C (post_id)');
        $this->addSql('ALTER TABLE payment DROP INDEX IDX_6D28840DA76ED395, ADD UNIQUE INDEX UNIQ_6D28840DA76ED395 (user_id)');
    }
}
