<?php

declare(strict_types=1);

namespace Rhsystem\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221224185923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changes the default user password';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE user SET password = '$2y$10\$LnED9pEb0R2st4oIPCjBq.H6AfMP6wQBgYEZuRk87P0tiDbLOYUzq' WHERE id = 1");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE user SET password = '$2y$10$0jQM58cfB5.gdDCS1NatO.6.9HjuITRGaiHcYIkQhawHS2.fg7IM6' WHERE id = 1");
    }
}
