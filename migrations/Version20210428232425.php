<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210428232425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE materiel ADD vote INT DEFAULT NULL, ADD rate DOUBLE PRECISION DEFAULT NULL');
     }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emploi_du_temps DROP FOREIGN KEY FK_F86B32C1451A37C2');
        $this->addSql('ALTER TABLE emploi_du_temps DROP FOREIGN KEY FK_F86B32C1732431A7');
        $this->addSql('ALTER TABLE emploi_du_temps CHANGE idzone idzone INT NOT NULL, CHANGE IdSeance IdSeance INT NOT NULL');
        $this->addSql('ALTER TABLE emploi_du_temps ADD CONSTRAINT fkseance FOREIGN KEY (IdSeance) REFERENCES seance (IdSeance) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emploi_du_temps ADD CONSTRAINT fkzone FOREIGN KEY (idzone) REFERENCES zonedacces (idzone) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emploi_du_temps RENAME INDEX fk_sea TO fkseance');
        $this->addSql('ALTER TABLE emploi_du_temps RENAME INDEX fk_zoo TO fkzone');
        $this->addSql('ALTER TABLE materiel DROP vote, DROP rate');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404C54C8C93');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404D5E86FF');
        $this->addSql('ALTER TABLE reclamation CHANGE user_id user_id INT NOT NULL, CHANGE type_id type_id INT NOT NULL, CHANGE etat_id etat_id INT NOT NULL, CHANGE daterec daterec DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE roles CHANGE role role VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E938D9C4A');
        $this->addSql('ALTER TABLE seance CHANGE activiteid activiteid INT NOT NULL');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT fk_seance2 FOREIGN KEY (activiteid) REFERENCES activite (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(140) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles JSON DEFAULT NULL COMMENT \'
        \', CHANGE birthDay birthDay VARCHAR(140) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(140) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pays pays VARCHAR(140) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adress adress VARCHAR(140) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE gender gender VARCHAR(140) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE idcode idcode VARCHAR(140) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
