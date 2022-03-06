<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220306013233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adiciona permissões iniciais ao sistema';
    }

    public function up(Schema $schema): void
    {
	    $this->addSql("INSERT INTO permission (name) VALUES ('user:read'), ('user:create'), ('user:edit')");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("truncate permission");
    }
}
