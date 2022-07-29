<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729234755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adicionada coluna que indica ordem da execução das queries em uma requisição';
    }

    public function up(Schema $schema): void
    {
        $schema->getTable("sql_logs")->addColumn("execution_order", "integer")->setDefault(1)->setNotnull(true);
    }

    public function down(Schema $schema): void
    {
        $schema->getTable("sql_logs")->dropColumn("execution_order");
    }
}
