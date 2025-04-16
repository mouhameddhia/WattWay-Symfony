<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404220205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment ADD id_assignment INT AUTO_INCREMENT NOT NULL, ADD description_assignment VARCHAR(255) NOT NULL, ADD status_assignment VARCHAR(255) NOT NULL, ADD id_car INT NOT NULL, DROP idAssignment, DROP descriptionAssignment, DROP statusAssignment, DROP idCar, CHANGE idMechanic id_mechanic INT DEFAULT NULL, CHANGE dateAssignment date_assignment DATETIME DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id_assignment)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX mechanic_id ON assignment_mechanics
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON assignment_mechanics
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics CHANGE assignment_id assignment_id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics ADD PRIMARY KEY (assignment_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bill MODIFY idBill INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_bill_car ON bill
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON bill
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bill ADD status_bill INT DEFAULT NULL, ADD id_user INT DEFAULT NULL, DROP idCar, DROP statusBill, CHANGE idBill id_bill INT AUTO_INCREMENT NOT NULL, CHANGE dateBill date_bill DATE NOT NULL, CHANGE totalAmountBill total_amount_bill DOUBLE PRECISION NOT NULL, CHANGE idUser id_car INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bill ADD PRIMARY KEY (id_bill)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car MODIFY idCar INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY fk_car_warehouse
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON car
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY fk_car_warehouse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD brand_car VARCHAR(255) NOT NULL, ADD price_car DOUBLE PRECISION NOT NULL, ADD status_car VARCHAR(255) NOT NULL, ADD kilometrage_car DOUBLE PRECISION NOT NULL, ADD vin_code VARCHAR(255) DEFAULT NULL, DROP modelCar, DROP brandCar, DROP priceCar, DROP kilometrageCar, DROP vinCode, CHANGE idCar id_car INT AUTO_INCREMENT NOT NULL, CHANGE statusCar model_car VARCHAR(255) NOT NULL, CHANGE yearCar year_car INT NOT NULL, CHANGE imgCar img_car VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT FK_773DE69D1059A0AA FOREIGN KEY (idWarehouse) REFERENCES warehouse (idWarehouse)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD PRIMARY KEY (id_car)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_car_warehouse ON car
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_773DE69D1059A0AA ON car (idWarehouse)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT fk_car_warehouse FOREIGN KEY (idWarehouse) REFERENCES warehouse (idWarehouse) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback MODIFY idFeedback INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idUser ON feedback
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON feedback
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD content_feedback LONGTEXT NOT NULL, ADD date_feedback DATETIME DEFAULT NULL, DROP contentFeedback, DROP dateFeedback, CHANGE idFeedback id_feedback INT AUTO_INCREMENT NOT NULL, CHANGE ratingFeedback rating_feedback INT DEFAULT NULL, CHANGE idUser id_user INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD PRIMARY KEY (id_feedback)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item MODIFY idItem INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX orderId ON item
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD category_item VARCHAR(255) NOT NULL, DROP categoryItem, CHANGE idItem id_item INT AUTO_INCREMENT NOT NULL, CHANGE nameItem name_item VARCHAR(255) NOT NULL, CHANGE quantityItem quantity_item INT NOT NULL, CHANGE pricePerUnitItem price_per_unit_item DOUBLE PRECISION NOT NULL, CHANGE orderId order_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD PRIMARY KEY (id_item)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mechanic MODIFY idMechanic INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON mechanic
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mechanic ADD name_mechanic VARCHAR(255) NOT NULL, ADD speciality_mechanic VARCHAR(255) NOT NULL, ADD email_mechanic VARCHAR(255) NOT NULL, ADD cars_repaired INT DEFAULT NULL, DROP nameMechanic, DROP specialityMechanic, DROP emailMechanic, DROP carsRepaired, CHANGE idMechanic id_mechanic INT AUTO_INCREMENT NOT NULL, CHANGE imgMechanic img_mechanic VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mechanic ADD PRIMARY KEY (id_mechanic)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` MODIFY idOrder INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_admin ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD supplier_order VARCHAR(255) NOT NULL, ADD date_order VARCHAR(255) NOT NULL, ADD status_order VARCHAR(255) NOT NULL, ADD address_supplier_order VARCHAR(255) NOT NULL, DROP supplierOrder, DROP dateOrder, DROP statusOrder, DROP addressSupplierOrder, CHANGE idOrder id_order INT AUTO_INCREMENT NOT NULL, CHANGE totalAmountOrder total_amount_order DOUBLE PRECISION NOT NULL, CHANGE idAdmin id_admin INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD PRIMARY KEY (id_order)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response MODIFY idResponse INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idUser ON response
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idSubmission ON response
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON response
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD id_user INT NOT NULL, ADD id_submission INT NOT NULL, DROP idUser, DROP idSubmission, CHANGE message message LONGTEXT NOT NULL, CHANGE idResponse id_response INT AUTO_INCREMENT NOT NULL, CHANGE dateResponse date_response DATE NOT NULL, CHANGE typeResponse type_response VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD PRIMARY KEY (id_response)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission MODIFY idSubmission INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idCar ON submission
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idUser ON submission
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON submission
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD id_car INT NOT NULL, ADD id_user INT NOT NULL, ADD preferred_contact_method VARCHAR(255) DEFAULT NULL, DROP idCar, DROP idUser, DROP preferredContactMethod, CHANGE description description LONGTEXT NOT NULL, CHANGE last_modified last_modified DATETIME NOT NULL, CHANGE idSubmission id_submission INT AUTO_INCREMENT NOT NULL, CHANGE urgencyLevel urgency_level VARCHAR(255) NOT NULL, CHANGE dateSubmission date_submission DATE NOT NULL, CHANGE preferredAppointmentDate preferred_appointment_date DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD PRIMARY KEY (id_submission)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user MODIFY idUser INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD email_user VARCHAR(255) NOT NULL, ADD password_user VARCHAR(255) NOT NULL, ADD first_name_user VARCHAR(255) NOT NULL, ADD last_name_user VARCHAR(255) NOT NULL, ADD role_user VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(255) DEFAULT NULL, ADD payment_details VARCHAR(255) NOT NULL, ADD function_admin VARCHAR(255) DEFAULT NULL, ADD profile_picture VARCHAR(255) DEFAULT NULL, DROP emailUser, DROP passwordUser, DROP firstNameUser, DROP lastNameUser, DROP roleUser, DROP phoneNumber, DROP paymentDetails, DROP functionAdmin, DROP profilePicture, CHANGE idUser id_user INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD PRIMARY KEY (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse MODIFY idWarehouse INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON warehouse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse ADD postal_code VARCHAR(255) NOT NULL, DROP postalCode, CHANGE city city VARCHAR(255) NOT NULL, CHANGE idWarehouse id_warehouse INT AUTO_INCREMENT NOT NULL, CHANGE capacityWarehouse capacity_warehouse INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse ADD PRIMARY KEY (id_warehouse)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE admin (id_admin INT NOT NULL, function_admin VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, name_admin VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_admin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment MODIFY id_assignment INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON assignment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment ADD descriptionAssignment VARCHAR(500) NOT NULL, ADD statusAssignment VARCHAR(30) NOT NULL, ADD idCar INT NOT NULL, DROP id_assignment, DROP description_assignment, DROP status_assignment, CHANGE id_car idAssignment INT NOT NULL, CHANGE id_mechanic idMechanic INT DEFAULT NULL, CHANGE date_assignment dateAssignment DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment ADD PRIMARY KEY (idAssignment)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics MODIFY assignment_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON assignment_mechanics
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics CHANGE assignment_id assignment_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX mechanic_id ON assignment_mechanics (mechanic_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE assignment_mechanics ADD PRIMARY KEY (assignment_id, mechanic_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bill MODIFY id_bill INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON bill
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bill ADD idCar INT DEFAULT 0, ADD statusBill INT DEFAULT 0, ADD idUser INT DEFAULT NULL, DROP id_car, DROP status_bill, DROP id_user, CHANGE id_bill idBill INT AUTO_INCREMENT NOT NULL, CHANGE date_bill dateBill DATE NOT NULL, CHANGE total_amount_bill totalAmountBill DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_bill_car ON bill (idCar)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bill ADD PRIMARY KEY (idBill)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car MODIFY id_car INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY FK_773DE69D1059A0AA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON car
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY FK_773DE69D1059A0AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD modelCar VARCHAR(100) NOT NULL, ADD brandCar VARCHAR(100) NOT NULL, ADD priceCar DOUBLE PRECISION NOT NULL, ADD statusCar VARCHAR(255) NOT NULL, ADD kilometrageCar DOUBLE PRECISION NOT NULL, ADD imgCar VARCHAR(255) DEFAULT NULL, ADD vinCode VARCHAR(17) DEFAULT NULL, DROP model_car, DROP brand_car, DROP price_car, DROP status_car, DROP kilometrage_car, DROP img_car, DROP vin_code, CHANGE id_car idCar INT AUTO_INCREMENT NOT NULL, CHANGE year_car yearCar INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT fk_car_warehouse FOREIGN KEY (idWarehouse) REFERENCES warehouse (idWarehouse) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD PRIMARY KEY (idCar)
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
            ALTER TABLE feedback MODIFY id_feedback INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON feedback
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD contentFeedback TEXT NOT NULL, ADD dateFeedback DATETIME DEFAULT CURRENT_TIMESTAMP, DROP content_feedback, DROP date_feedback, CHANGE id_feedback idFeedback INT AUTO_INCREMENT NOT NULL, CHANGE rating_feedback ratingFeedback INT DEFAULT NULL, CHANGE id_user idUser INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idUser ON feedback (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feedback ADD PRIMARY KEY (idFeedback)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item MODIFY id_item INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD nameItem VARCHAR(255) NOT NULL, ADD categoryItem VARCHAR(100) NOT NULL, DROP name_item, DROP category_item, CHANGE id_item idItem INT AUTO_INCREMENT NOT NULL, CHANGE quantity_item quantityItem INT NOT NULL, CHANGE price_per_unit_item pricePerUnitItem DOUBLE PRECISION NOT NULL, CHANGE order_id orderId INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX orderId ON item (orderId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD PRIMARY KEY (idItem)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mechanic MODIFY id_mechanic INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON mechanic
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mechanic ADD nameMechanic VARCHAR(255) NOT NULL, ADD specialityMechanic VARCHAR(255) NOT NULL, ADD emailMechanic VARCHAR(255) NOT NULL, ADD carsRepaired INT DEFAULT 0, DROP name_mechanic, DROP speciality_mechanic, DROP email_mechanic, DROP cars_repaired, CHANGE id_mechanic idMechanic INT AUTO_INCREMENT NOT NULL, CHANGE img_mechanic imgMechanic VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mechanic ADD PRIMARY KEY (idMechanic)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` MODIFY id_order INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD supplierOrder VARCHAR(255) NOT NULL, ADD dateOrder VARCHAR(55) NOT NULL, ADD statusOrder VARCHAR(255) NOT NULL, ADD addressSupplierOrder VARCHAR(255) NOT NULL, DROP supplier_order, DROP date_order, DROP status_order, DROP address_supplier_order, CHANGE id_order idOrder INT AUTO_INCREMENT NOT NULL, CHANGE total_amount_order totalAmountOrder DOUBLE PRECISION NOT NULL, CHANGE id_admin idAdmin INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_admin ON `order` (idAdmin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD PRIMARY KEY (idOrder)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response MODIFY id_response INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON response
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD idUser INT NOT NULL, ADD idSubmission INT NOT NULL, DROP id_user, DROP id_submission, CHANGE message message TEXT NOT NULL, CHANGE id_response idResponse INT AUTO_INCREMENT NOT NULL, CHANGE date_response dateResponse DATE NOT NULL, CHANGE type_response typeResponse VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idUser ON response (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idSubmission ON response (idSubmission)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE response ADD PRIMARY KEY (idResponse)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission MODIFY id_submission INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON submission
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD idCar INT NOT NULL, ADD idUser INT NOT NULL, ADD preferredContactMethod VARCHAR(50) DEFAULT NULL, DROP id_car, DROP id_user, DROP preferred_contact_method, CHANGE description description TEXT NOT NULL, CHANGE last_modified last_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE id_submission idSubmission INT AUTO_INCREMENT NOT NULL, CHANGE urgency_level urgencyLevel VARCHAR(255) NOT NULL, CHANGE date_submission dateSubmission DATE NOT NULL, CHANGE preferred_appointment_date preferredAppointmentDate DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idCar ON submission (idCar)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idUser ON submission (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE submission ADD PRIMARY KEY (idSubmission)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user MODIFY id_user INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD emailUser VARCHAR(100) NOT NULL, ADD passwordUser VARCHAR(255) NOT NULL, ADD firstNameUser VARCHAR(50) NOT NULL, ADD lastNameUser VARCHAR(50) NOT NULL, ADD roleUser VARCHAR(255) NOT NULL, ADD phoneNumber VARCHAR(20) DEFAULT NULL, ADD paymentDetails VARCHAR(255) NOT NULL, ADD functionAdmin VARCHAR(255) DEFAULT NULL, ADD profilePicture VARCHAR(255) DEFAULT NULL, DROP email_user, DROP password_user, DROP first_name_user, DROP last_name_user, DROP role_user, DROP phone_number, DROP payment_details, DROP function_admin, DROP profile_picture, CHANGE id_user idUser INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD PRIMARY KEY (idUser)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse MODIFY id_warehouse INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON warehouse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse ADD postalCode VARCHAR(20) NOT NULL, DROP postal_code, CHANGE city city VARCHAR(100) NOT NULL, CHANGE id_warehouse idWarehouse INT AUTO_INCREMENT NOT NULL, CHANGE capacity_warehouse capacityWarehouse INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse ADD PRIMARY KEY (idWarehouse)
        SQL);
    }
}
