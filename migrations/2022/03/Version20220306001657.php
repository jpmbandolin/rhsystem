<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220306001657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds permition tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
			CREATE TABLE IF NOT EXISTS `permission` (
				`id` INT NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(50) NOT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE='latin1_swedish_ci';
		");

		$this->addSql("
			CREATE TABLE `user_permission` (
				`user_id` INT NOT NULL,
				`permission_id` INT NOT NULL,
				PRIMARY KEY (`user_id`, `permission_id`),
				CONSTRAINT `FK__user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
				CONSTRAINT `FK__permission` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
			)
			COLLATE='latin1_swedish_ci';
		");
    }

    public function down(Schema $schema): void
    {
		$this->addSql("DROP TABLE `user_permission`");
        $this->addSql("DROP TABLE `permission`");

    }
}
