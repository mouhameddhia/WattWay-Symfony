<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404060911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY fk_car_warehouse
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `admin`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE assignment
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
            ALTER TABLE `order` DROP FOREIGN KEY fk_admin
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_admin ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE idOrder idOrder INT NOT NULL, CHANGE dateOrder dateOrder DATETIME NOT NULL, CHANGE totalAmountOrder totalAmountOrder DOUBLE PRECISION NOT NULL, CHANGE statusOrder statusOrder VARCHAR(100) NOT NULL, CHANGE idAdmin idAdmin INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE `admin` (idAdmin INT NOT NULL, functionAdmin VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nameAdmin VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX idAdmin (idAdmin), PRIMARY KEY(idAdmin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE assignment (idAssignment INT AUTO_INCREMENT NOT NULL, descriptionAssignment VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, statusAssignment VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idUser INT DEFAULT NULL, idMechanic INT DEFAULT NULL, INDEX idMechanic (idMechanic), INDEX idUser (idUser), PRIMARY KEY(idAssignment)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE assignment_mechanics (assignment_id INT NOT NULL, mechanic_id INT NOT NULL, INDEX mechanic_id (mechanic_id), PRIMARY KEY(assignment_id, mechanic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE bill (idBill INT AUTO_INCREMENT NOT NULL, dateBill DATE NOT NULL, totalAmountBill FLOAT NOT NULL, idCar INT DEFAULT 0, statusBill INT DEFAULT 0, idUser INT DEFAULT NULL, INDEX fk_bill_car (idCar), PRIMARY KEY(idBill)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE car (idCar INT AUTO_INCREMENT NOT NULL, modelCar VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, brandCar VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, yearCar INT NOT NULL, priceCar FLOAT NOT NULL, statusCar ENUM('available', 'under repair', 'not available') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, kilometrageCar FLOAT NOT NULL, idWarehouse INT DEFAULT NULL, imgCar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT 'NULL' COLLATE `utf8mb4_general_ci`, INDEX fk_car_warehouse (idWarehouse), PRIMARY KEY(idCar)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feedback (idFeedback INT AUTO_INCREMENT NOT NULL, contentFeedback TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, ratingFeedback INT DEFAULT NULL, dateFeedback DATETIME DEFAULT 'current_timestamp()', idUser INT NOT NULL, INDEX idUser (idUser), PRIMARY KEY(idFeedback)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE item (idItem INT AUTO_INCREMENT NOT NULL, nameItem VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, quantityItem INT NOT NULL, pricePerUnitItem FLOAT NOT NULL, categoryItem VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, orderId INT DEFAULT NULL, INDEX orderId (orderId), PRIMARY KEY(idItem)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mechanic (idMechanic INT AUTO_INCREMENT NOT NULL, nameMechanic VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, specialityMechanic VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(idMechanic)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE response (idResponse INT AUTO_INCREMENT NOT NULL, message TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateResponse DATE NOT NULL, typeResponse ENUM('Acknowledgment', 'Resolution', 'ClarificationRequest') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idUser INT NOT NULL, idSubmission INT NOT NULL, INDEX idUser (idUser), INDEX idSubmission (idSubmission), PRIMARY KEY(idResponse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE submission (idSubmission INT AUTO_INCREMENT NOT NULL, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, status ENUM('PENDING', 'APPROVED', 'RESPONDED') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, urgencyLevel ENUM('LOW', 'MEDIUM', 'HIGH') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateSubmission DATE NOT NULL, idCar INT NOT NULL, idUser INT NOT NULL, INDEX idCar (idCar), INDEX idUser (idUser), PRIMARY KEY(idSubmission)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (idUser INT AUTO_INCREMENT NOT NULL, emailUser VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, passwordUser VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, firstNameUser VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, lastNameUser VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, roleUser ENUM('ADMIN', 'CLIENT') CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, phoneNumber VARCHAR(20) CHARACTER SET latin1 DEFAULT 'NULL' COLLATE `latin1_swedish_ci`, paymentDetails ENUM('PAYPAL', 'CREDIT_CARD', 'BANK_TRANSFER') CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, address VARCHAR(255) CHARACTER SET latin1 DEFAULT 'NULL' COLLATE `latin1_swedish_ci`, functionAdmin ENUM('MANAGER', 'HEAD_OF_MECHANICS') CHARACTER SET latin1 DEFAULT 'NULL' COLLATE `latin1_swedish_ci`, profilePicture VARCHAR(255) CHARACTER SET latin1 DEFAULT 'NULL' COLLATE `latin1_swedish_ci`, PRIMARY KEY(idUser)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE warehouse (idWarehouse INT AUTO_INCREMENT NOT NULL, city VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, street VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, postalCode VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, capacityWarehouse INT NOT NULL, PRIMARY KEY(idWarehouse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT fk_car_warehouse FOREIGN KEY (idWarehouse) REFERENCES warehouse (idWarehouse) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE idOrder idOrder INT AUTO_INCREMENT NOT NULL, CHANGE dateOrder dateOrder VARCHAR(55) NOT NULL, CHANGE totalAmountOrder totalAmountOrder FLOAT NOT NULL, CHANGE statusOrder statusOrder VARCHAR(255) NOT NULL, CHANGE idAdmin idAdmin INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD CONSTRAINT fk_admin FOREIGN KEY (idAdmin) REFERENCES `admin` (idAdmin)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_admin ON `order` (idAdmin)
        SQL);
    }
}
