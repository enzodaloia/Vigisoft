<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428080051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ADD statut_id INT NOT NULL, ADD severite_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4ED9D9C26 FOREIGN KEY (severite_id) REFERENCES severite (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_54469DF4F6203804 ON tickets (statut_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_54469DF4ED9D9C26 ON tickets (severite_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4F6203804
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4ED9D9C26
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_54469DF4F6203804 ON tickets
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_54469DF4ED9D9C26 ON tickets
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets DROP statut_id, DROP severite_id
        SQL);
    }
}
