<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507232224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE car CHANGE modelCar modelCar VARCHAR(255) NOT NULL, CHANGE brandCar brandCar VARCHAR(255) NOT NULL, CHANGE vinCode vinCode VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT FK_773DE69D1059A0AA FOREIGN KEY (idWarehouse) REFERENCES warehouse (idWarehouse)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT FK_773DE69DFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_773DE69DFE6E88D7 ON car (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_car_warehouse ON car
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_773DE69D1059A0AA ON car (idWarehouse)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback CHANGE contentFeedback contentFeedback LONGTEXT NOT NULL, CHANGE idUser idUser INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX iduser ON feedback
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D2294458FE6E88D7 ON feedback (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX orderId ON item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item CHANGE categoryItem categoryItem VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_admin ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE dateOrder dateOrder DATETIME NOT NULL, CHANGE statusOrder statusOrder VARCHAR(100) NOT NULL, CHANGE idAdmin idAdmin INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request CHANGE expires_at expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE requested_at requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_reset_password_user ON reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idUser ON response
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idSubmission ON response
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response CHANGE message message LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idUser ON submission
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission CHANGE description description LONGTEXT NOT NULL, CHANGE idCar idCar INT DEFAULT NULL, CHANGE last_modified last_modified DATETIME NOT NULL, CHANGE preferredContactMethod preferredContactMethod VARCHAR(255) DEFAULT NULL, CHANGE preferredAppointmentDate preferredAppointmentDate DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD CONSTRAINT FK_DB055AF35675FB1A FOREIGN KEY (idCar) REFERENCES car (idCar)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idcar ON submission
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DB055AF35675FB1A ON submission (idCar)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE emailUser emailUser VARCHAR(255) NOT NULL, CHANGE firstNameUser firstNameUser VARCHAR(255) NOT NULL, CHANGE lastNameUser lastNameUser VARCHAR(255) NOT NULL, CHANGE phoneNumber phoneNumber VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E90E40CB ON user (emailUser)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E85E83E4 ON user (phoneNumber)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse CHANGE city city VARCHAR(255) NOT NULL, CHANGE postalCode postalCode VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY FK_773DE69D1059A0AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY FK_773DE69DFE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_773DE69DFE6E88D7 ON car
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY FK_773DE69D1059A0AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car CHANGE modelCar modelCar VARCHAR(100) NOT NULL, CHANGE brandCar brandCar VARCHAR(100) NOT NULL, CHANGE vinCode vinCode VARCHAR(17) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_773de69d1059a0aa ON car
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_car_warehouse ON car (idWarehouse)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT FK_773DE69D1059A0AA FOREIGN KEY (idWarehouse) REFERENCES warehouse (idWarehouse)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458FE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458FE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback CHANGE contentFeedback contentFeedback TEXT NOT NULL, CHANGE idUser idUser INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_d2294458fe6e88d7 ON feedback
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idUser ON feedback (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD CONSTRAINT FK_D2294458FE6E88D7 FOREIGN KEY (idUser) REFERENCES user (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `item` CHANGE categoryItem categoryItem VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX orderId ON `item` (orderId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE dateOrder dateOrder VARCHAR(55) NOT NULL, CHANGE statusOrder statusOrder VARCHAR(255) NOT NULL, CHANGE idAdmin idAdmin INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_admin ON `order` (idAdmin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request CHANGE requested_at requested_at DATETIME NOT NULL, CHANGE expires_at expires_at DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_7ce748aa76ed395 ON reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_RESET_PASSWORD_USER ON reset_password_request (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response CHANGE message message TEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idUser ON response (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idSubmission ON response (idSubmission)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF35675FB1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF35675FB1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission CHANGE description description TEXT NOT NULL, CHANGE last_modified last_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE preferredContactMethod preferredContactMethod VARCHAR(50) DEFAULT NULL, CHANGE preferredAppointmentDate preferredAppointmentDate DATE DEFAULT NULL, CHANGE idCar idCar INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idUser ON submission (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_db055af35675fb1a ON submission
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idCar ON submission (idCar)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD CONSTRAINT FK_DB055AF35675FB1A FOREIGN KEY (idCar) REFERENCES car (idCar)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649E90E40CB ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649E85E83E4 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE emailUser emailUser VARCHAR(100) NOT NULL, CHANGE firstNameUser firstNameUser VARCHAR(50) NOT NULL, CHANGE lastNameUser lastNameUser VARCHAR(50) NOT NULL, CHANGE phoneNumber phoneNumber VARCHAR(20) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse CHANGE city city VARCHAR(100) NOT NULL, CHANGE postalCode postalCode VARCHAR(20) NOT NULL
        SQL);
    }
}
