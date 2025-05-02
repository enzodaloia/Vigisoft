<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428145447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contribution ADD created_by_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EA351E15B03A8386 ON contribution (created_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EA351E15B03A8386 ON contribution
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contribution DROP created_by_id
        SQL);
    }
}
