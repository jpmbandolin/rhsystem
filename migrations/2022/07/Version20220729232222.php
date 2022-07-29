<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729232222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona tabela para registrar logs de SQL';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE `sql_logs` (
				`request_id` VARCHAR(50) NOT NULL,
				`first_clause` VARCHAR(10) NULL DEFAULT NULL,
				`target_table` VARCHAR(50) NULL DEFAULT NULL,
				`query` TEXT NOT NULL,
				`json_encoded_args` TEXT NOT NULL,
				`executed_by` INT NULL DEFAULT NULL
			)
			COLLATE='latin1_swedish_ci';
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE sql_logs");
    }
}
