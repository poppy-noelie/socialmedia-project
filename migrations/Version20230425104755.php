<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425104755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE following (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, target_user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_71BF8DE3A76ED395 (user_id), INDEX IDX_71BF8DE36C066AFE (target_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AC6340B34B89032C (post_id), INDEX IDX_AC6340B3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE36C066AFE FOREIGN KEY (target_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B34B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE3A76ED395');
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE36C066AFE');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B34B89032C');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
