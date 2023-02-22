<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221111038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE admin_sponsored');
        $this->addSql('DROP TABLE tb_activity_1');
        $this->addSql('DROP TABLE tb_activity_2');
        $this->addSql('DROP TABLE tb_activity_3');
        $this->addSql('DROP TABLE tb_carte_1');
        $this->addSql('DROP TABLE tb_carte_2');
        $this->addSql('DROP TABLE tb_carte_3');
        $this->addSql('DROP TABLE tb_friends_1');
        $this->addSql('DROP TABLE tb_friends_2');
        $this->addSql('DROP TABLE tb_friends_3');
        $this->addSql('DROP TABLE tb_message_1');
        $this->addSql('DROP TABLE tb_message_2');
        $this->addSql('DROP TABLE tb_message_3');
        $this->addSql('DROP TABLE tb_notification_1');
        $this->addSql('DROP TABLE tb_notification_2');
        $this->addSql('DROP TABLE tb_notification_3');
        $this->addSql('DROP TABLE tb_publication_1');
        $this->addSql('DROP TABLE tb_publication_2');
        $this->addSql('DROP TABLE tb_publication_3');
        $this->addSql('ALTER TABLE user ADD datetime DATETIME NOT NULL, CHANGE profil profil VARCHAR(255) NOT NULL, CHANGE couverture couverture VARCHAR(255) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL, CHANGE tablemessage tablemessage VARCHAR(255) NOT NULL, CHANGE tablenotification tablenotification VARCHAR(255) NOT NULL, CHANGE tablecarte tablecarte VARCHAR(255) NOT NULL, CHANGE tablepublication tablepublication VARCHAR(255) NOT NULL, CHANGE tableactivity tableactivity VARCHAR(255) NOT NULL, CHANGE tablefriends tablefriends VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_sponsored (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, lasted DOUBLE PRECISION NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_activity_1 (id INT AUTO_INCREMENT NOT NULL, product VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, devise VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, user_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, notPrice DOUBLE PRECISION NOT NULL, taxe DOUBLE PRECISION DEFAULT \'0\' NOT NULL, quantity DOUBLE PRECISION NOT NULL, photos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', isSale TINYINT(1) DEFAULT 1 NOT NULL, isDelivery TINYINT(1) DEFAULT 0 NOT NULL, status TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_activity_2 (id INT AUTO_INCREMENT NOT NULL, product VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, devise VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, user_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, notPrice DOUBLE PRECISION NOT NULL, taxe DOUBLE PRECISION DEFAULT \'0\' NOT NULL, quantity DOUBLE PRECISION NOT NULL, photos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', isSale TINYINT(1) DEFAULT 1 NOT NULL, isDelivery TINYINT(1) DEFAULT 0 NOT NULL, status TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_activity_3 (id INT AUTO_INCREMENT NOT NULL, product VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, devise VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, user_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, notPrice DOUBLE PRECISION NOT NULL, taxe DOUBLE PRECISION DEFAULT \'0\' NOT NULL, quantity DOUBLE PRECISION NOT NULL, photos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', isSale TINYINT(1) DEFAULT 1 NOT NULL, isDelivery TINYINT(1) DEFAULT 0 NOT NULL, status TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_carte_1 (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, status TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_carte_2 (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, status TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_carte_3 (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, status TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_friends_1 (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, is_follower TINYINT(1) DEFAULT 0 NOT NULL, is_wait TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_friends_2 (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, is_follower TINYINT(1) DEFAULT 0 NOT NULL, is_wait TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_friends_3 (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, is_follower TINYINT(1) DEFAULT 0 NOT NULL, is_wait TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_message_1 (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, isForMe TINYINT(1) DEFAULT 0 NOT NULL, is_read TINYINT(1) DEFAULT 0 NOT NULL, is_show TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_message_2 (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, isForMe TINYINT(1) DEFAULT 0 NOT NULL, is_read TINYINT(1) DEFAULT 0 NOT NULL, is_show TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_message_3 (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, isForMe TINYINT(1) DEFAULT 0 NOT NULL, is_read TINYINT(1) DEFAULT 0 NOT NULL, is_show TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_notification_1 (id INT AUTO_INCREMENT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, sender_id INT NOT NULL, type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, is_read TINYINT(1) DEFAULT 0 NOT NULL, is_show TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_notification_2 (id INT AUTO_INCREMENT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, sender_id INT NOT NULL, type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, is_read TINYINT(1) DEFAULT 0 NOT NULL, is_show TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_notification_3 (id INT AUTO_INCREMENT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, sender_id INT NOT NULL, type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, is_read TINYINT(1) DEFAULT 0 NOT NULL, is_show TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_publication_1 (id INT AUTO_INCREMENT NOT NULL, publication VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, confidentiality TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, photos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', isWait TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_publication_2 (id INT AUTO_INCREMENT NOT NULL, publication VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, confidentiality TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, photos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', isWait TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tb_publication_3 (id INT AUTO_INCREMENT NOT NULL, publication VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, confidentiality TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, photos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', isWait TINYINT(1) DEFAULT 0 NOT NULL, datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE user DROP datetime, CHANGE tablemessage tablemessage VARCHAR(255) DEFAULT NULL, CHANGE tablenotification tablenotification VARCHAR(255) DEFAULT NULL, CHANGE tablecarte tablecarte VARCHAR(255) DEFAULT NULL, CHANGE tablepublication tablepublication VARCHAR(255) DEFAULT NULL, CHANGE tableactivity tableactivity VARCHAR(255) DEFAULT NULL, CHANGE tablefriends tablefriends VARCHAR(255) DEFAULT NULL, CHANGE profil profil VARCHAR(255) DEFAULT NULL, CHANGE couverture couverture VARCHAR(255) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
