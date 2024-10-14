<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014104833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494BD2A4C0');
        $this->addSql('DROP INDEX IDX_8D93D6494BD2A4C0 ON user');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, DROP report_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` ADD report_id INT NOT NULL, DROP is_verified');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6494BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D6494BD2A4C0 ON `user` (report_id)');
    }
}
