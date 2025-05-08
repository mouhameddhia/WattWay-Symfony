<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507232640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
            ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458FE6E88D7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
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
            DROP INDEX UNIQ_8D93D649E85E83E4 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse CHANGE city city VARCHAR(100) NOT NULL, CHANGE postalCode postalCode VARCHAR(20) NOT NULL
        SQL);
    }
}
