<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729232849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona coluna para gravar data de criação dos logs.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE `sql_logs`
				ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP() AFTER `executed_by`;
        ");

    }

    public function down(Schema $schema): void
    {
		$schema->getTable("sql_logs")->dropColumn("created_at");
    }
}
