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
        return 'Initial Migration';
    }

    public function up(Schema $schema): void
    {
		$this->addSql("
			/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
			/*!40101 SET NAMES utf8 */;
			/*!50503 SET NAMES utf8mb4 */;
			/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
			/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
			/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
		");

        $this->addSql("
			CREATE TABLE IF NOT EXISTS `candidate` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `created_by` int(11) NOT NULL,
			  `name` varchar(150) NOT NULL,
			  `email` varchar(150) DEFAULT NULL,
			  `photo_id` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `FK_candidate_user` (`created_by`),
			  KEY `FK_candidate_file` (`photo_id`),
			  CONSTRAINT `FK_candidate_file` FOREIGN KEY (`photo_id`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  CONSTRAINT `FK_candidate_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
		");
		
		$this->addSql("
			CREATE TABLE IF NOT EXISTS `candidate_resume` (
			  `candidate_id` int(11) NOT NULL,
			  `file_id` int(11) NOT NULL,
			  `creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`candidate_id`,`file_id`),
			  KEY `FK_candidate_resume_file` (`file_id`),
			  CONSTRAINT `FK_candidate_resume_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  CONSTRAINT `FK_candidate_resume_file` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
		
		$this->addSql("
			CREATE TABLE IF NOT EXISTS `candidate_test` (
			  `file_id` int(11) NOT NULL,
			  `candidate_id` int(11) NOT NULL,
			  `result` varchar(50) DEFAULT NULL,
			  `creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`file_id`,`candidate_id`),
			  KEY `FK_candidate_test_candidate` (`candidate_id`),
			  CONSTRAINT `FK_candidate_test_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  CONSTRAINT `FK_candidate_test_file` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
		
		$this->addSql("
			CREATE TABLE IF NOT EXISTS `candidate_test_comment` (
			  `file_id` int(11) NOT NULL,
			  `comment_id` int(11) NOT NULL,
			  `creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`file_id`,`comment_id`),
			  KEY `FK_candidate_test_comment_comment` (`comment_id`),
			  CONSTRAINT `FK_candidate_test_comment_comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  CONSTRAINT `FK_candidate_test_comment_file` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
		
		$this->addSql("
			CREATE TABLE IF NOT EXISTS `comment` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `author_id` int(11) NOT NULL,
			  `comment` varchar(200) NOT NULL,
			  `status` enum('ACTIVE','INACTIVE','DELETED') NOT NULL DEFAULT 'ACTIVE',
			  PRIMARY KEY (`id`),
			  KEY `FK_comment_user` (`author_id`),
			  CONSTRAINT `FK_comment_user` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
		");
		
		$this->addSql("
			CREATE TABLE IF NOT EXISTS `file` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `created_by` int(11) NOT NULL,
			  `user_friendly_name` varchar(150) NOT NULL,
			  `type` varchar(50) NOT NULL,
			  `name` varchar(150) NOT NULL,
			  `creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `status` enum('ACTIVE','INACTIVE','DELETED') NOT NULL DEFAULT 'ACTIVE',
			  PRIMARY KEY (`id`),
			  KEY `FK_file_user` (`created_by`),
			  CONSTRAINT `FK_file_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
		");
		
		$this->addSql("
			CREATE TABLE IF NOT EXISTS `user` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(180) NOT NULL,
			  `email` varchar(150) NOT NULL,
			  `password` varchar(150) NOT NULL,
			  `status` enum('ACTIVE','INACTIVE','DELETED') NOT NULL,
			  PRIMARY KEY (`id`) USING BTREE,
			  UNIQUE KEY `email` (`email`) USING BTREE
			) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
		");
		
		$this->addSql("
			CREATE TABLE IF NOT EXISTS `_doctrine_migration_versions` (
			  `version` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
			  `executed_at` datetime DEFAULT NULL,
			  `execution_time` int(11) DEFAULT NULL,
			  PRIMARY KEY (`version`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		$this->addSql("
			/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
			/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
			/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
			/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
		");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException("Cannot reverse initial migration");
    }
}
