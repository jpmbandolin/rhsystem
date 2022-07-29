<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729134731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creation of first user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
			INSERT INTO `user` (`id`, `name`, `email`, `password`, `status`)
			VALUES (1, 'Admin', 'admin@rhsystem.com', '$2y$10$0jQM58cfB5.gdDCS1NatO.6.9HjuITRGaiHcYIkQhawHS2.fg7IM6', 'ACTIVE');
		");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException("Creation of first user cannot be reverted");
    }
}
