<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414222548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE admin (id_admin INT NOT NULL, function_admin VARCHAR(100) NOT NULL, name_admin VARCHAR(255) NOT NULL, PRIMARY KEY(id_admin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE assignment_mechanics (idAssignment INT NOT NULL, idMechanic INT NOT NULL, INDEX IDX_FFB535AAB2315A50 (idAssignment), INDEX IDX_FFB535AA2294F422 (idMechanic), PRIMARY KEY(idAssignment, idMechanic)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE bill (id_bill INT NOT NULL, date_bill DATE NOT NULL, total_amount_bill DOUBLE PRECISION NOT NULL, id_car INT NOT NULL, status_bill INT NOT NULL, id_user INT NOT NULL, PRIMARY KEY(id_bill)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE car (idCar INT NOT NULL, id_warehouse INT NOT NULL, model_car VARCHAR(100) NOT NULL, brand_car VARCHAR(100) NOT NULL, year_car INT NOT NULL, price_car DOUBLE PRECISION NOT NULL, kilometrage_car DOUBLE PRECISION NOT NULL, status_car VARCHAR(20) NOT NULL, img_car VARCHAR(255) NOT NULL, vin_code VARCHAR(17) NOT NULL, PRIMARY KEY(idCar)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feedback (id_feedback INT NOT NULL, content_feedback LONGTEXT NOT NULL, rating_feedback INT NOT NULL, date_feedback DATETIME NOT NULL, id_user INT NOT NULL, PRIMARY KEY(id_feedback)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE item (id_item INT NOT NULL, name_item VARCHAR(255) NOT NULL, quantity_item INT NOT NULL, price_per_unit_item DOUBLE PRECISION NOT NULL, category_item VARCHAR(100) NOT NULL, orderId INT DEFAULT NULL, INDEX IDX_1F1B251EFA237437 (orderId), PRIMARY KEY(id_item)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mechanic (idMechanic INT AUTO_INCREMENT NOT NULL, nameMechanic VARCHAR(255) NOT NULL, specialityMechanic VARCHAR(255) NOT NULL, imgMechanic VARCHAR(255) DEFAULT NULL, emailMechanic VARCHAR(255) NOT NULL, carsRepaired INT NOT NULL, PRIMARY KEY(idMechanic)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `order` (id_order INT NOT NULL, supplier_order VARCHAR(255) NOT NULL, date_order VARCHAR(55) NOT NULL, total_amount_order DOUBLE PRECISION NOT NULL, status_order VARCHAR(255) NOT NULL, id_admin INT NOT NULL, address_supplier_order VARCHAR(255) NOT NULL, PRIMARY KEY(id_order)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE response (id_response INT NOT NULL, message LONGTEXT NOT NULL, date_response DATE NOT NULL, type_response VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, idSubmission INT DEFAULT NULL, INDEX IDX_3E7B0BFBFE6E88D7 (idUser), INDEX IDX_3E7B0BFB59F14419 (idSubmission), PRIMARY KEY(id_response)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE submission (id_submission INT NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, urgency_level VARCHAR(255) NOT NULL, date_submission DATE NOT NULL, id_car INT NOT NULL, last_modified DATETIME NOT NULL, preferred_contact_method VARCHAR(50) NOT NULL, preferred_appointment_date DATE NOT NULL, idUser INT DEFAULT NULL, INDEX IDX_DB055AF3FE6E88D7 (idUser), PRIMARY KEY(id_submission)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id_user INT NOT NULL, email_user VARCHAR(100) NOT NULL, password_user VARCHAR(255) NOT NULL, first_name_user VARCHAR(50) NOT NULL, last_name_user VARCHAR(50) NOT NULL, role_user VARCHAR(255) NOT NULL, phone_number VARCHAR(20) NOT NULL, payment_details VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, function_admin VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE warehouse (id_warehouse INT NOT NULL, capacity_warehouse INT NOT NULL, city VARCHAR(100) NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(20) NOT NULL, PRIMARY KEY(id_warehouse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics ADD CONSTRAINT FK_FFB535AAB2315A50 FOREIGN KEY (idAssignment) REFERENCES assignment (idAssignment) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics ADD CONSTRAINT FK_FFB535AA2294F422 FOREIGN KEY (idMechanic) REFERENCES mechanic (idMechanic) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD CONSTRAINT FK_1F1B251EFA237437 FOREIGN KEY (orderId) REFERENCES `order` (idOrder) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFBFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB59F14419 FOREIGN KEY (idSubmission) REFERENCES submission (idSubmission) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3FE6E88D7 FOREIGN KEY (idUser) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA5675FB1A FOREIGN KEY (idCar) REFERENCES car (idCar) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BA5675FB1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics DROP FOREIGN KEY FK_FFB535AAB2315A50
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics DROP FOREIGN KEY FK_FFB535AA2294F422
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EFA237437
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFBFE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB59F14419
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF3FE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE assignment_mechanics
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE bill
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE car
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feedback
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mechanic
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `order`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE response
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE submission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE warehouse
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
