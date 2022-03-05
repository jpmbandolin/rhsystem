<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220305165555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE `user` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(180) NOT NULL COLLATE 'latin1_swedish_ci',
			`email` VARCHAR(150) NOT NULL COLLATE 'latin1_swedish_ci',
			`password` VARCHAR(150) NOT NULL COLLATE 'latin1_swedish_ci',
			PRIMARY KEY (`id`) USING BTREE,
			UNIQUE INDEX `email` (`email`) USING BTREE
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB;
		");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE `user`");
    }
}
