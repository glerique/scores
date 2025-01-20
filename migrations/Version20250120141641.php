<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120141641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_stats (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, game_id INT DEFAULT NULL, goal_count INT DEFAULT 0 NOT NULL, assist_count INT DEFAULT 0 NOT NULL, INDEX IDX_E8351CEC99E6F5DF (player_id), INDEX IDX_E8351CECE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_stats ADD CONSTRAINT FK_E8351CEC99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE player_stats ADD CONSTRAINT FK_E8351CECE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE player DROP goal_count');
        $this->addSql('ALTER TABLE team CHANGE score points INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_stats DROP FOREIGN KEY FK_E8351CEC99E6F5DF');
        $this->addSql('ALTER TABLE player_stats DROP FOREIGN KEY FK_E8351CECE48FD905');
        $this->addSql('DROP TABLE player_stats');
        $this->addSql('ALTER TABLE team CHANGE points score INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE player ADD goal_count INT DEFAULT 0 NOT NULL');
    }
}
