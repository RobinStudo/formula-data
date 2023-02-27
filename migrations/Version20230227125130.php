<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230227125130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add link between Driver and Team';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE driver ADD team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_11667CD9296CD8AE ON driver (team_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9296CD8AE');
        $this->addSql('DROP INDEX IDX_11667CD9296CD8AE ON driver');
        $this->addSql('ALTER TABLE driver DROP team_id');
    }
}
