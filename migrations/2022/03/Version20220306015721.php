<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220306015721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("TRUNCATE user_permission");
		$this->addSql("ALTER TABLE `user_permission` DROP COLUMN `permission_id`;");
	    $this->addSql("
			ALTER TABLE `user_permission`
				ADD COLUMN `name` VARCHAR(50) NOT NULL AFTER `user_id`,
				DROP PRIMARY KEY,
				ADD PRIMARY KEY (`user_id`, `name`);
		
		");
		$this->addSql("DROP TABLE permission");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('This migration is irreversible!');

    }
}
