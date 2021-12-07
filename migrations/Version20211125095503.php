<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211125095503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medecin (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, carte_vitale TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medecin_moyen_paiement (medecin_id INT NOT NULL, moyen_paiement_id INT NOT NULL, INDEX IDX_22E4C69D4F31A84 (medecin_id), INDEX IDX_22E4C69D9C7E259C (moyen_paiement_id), PRIMARY KEY(medecin_id, moyen_paiement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moyen_paiement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, date_naissance DATE NOT NULL, nom_naissance VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, le_medecin_id INT NOT NULL, le_patient_id INT NOT NULL, jour INT NOT NULL, heure TIME NOT NULL, duree TIME NOT NULL, INDEX IDX_65E8AA0A27F3652F (le_medecin_id), INDEX IDX_65E8AA0A4889EDD2 (le_patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, le_patient_id INT DEFAULT NULL, le_medecin_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, cp VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, sexe TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6494889EDD2 (le_patient_id), UNIQUE INDEX UNIQ_8D93D64927F3652F (le_medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medecin_moyen_paiement ADD CONSTRAINT FK_22E4C69D4F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medecin_moyen_paiement ADD CONSTRAINT FK_22E4C69D9C7E259C FOREIGN KEY (moyen_paiement_id) REFERENCES moyen_paiement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A27F3652F FOREIGN KEY (le_medecin_id) REFERENCES medecin (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A4889EDD2 FOREIGN KEY (le_patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6494889EDD2 FOREIGN KEY (le_patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64927F3652F FOREIGN KEY (le_medecin_id) REFERENCES medecin (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medecin_moyen_paiement DROP FOREIGN KEY FK_22E4C69D4F31A84');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A27F3652F');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64927F3652F');
        $this->addSql('ALTER TABLE medecin_moyen_paiement DROP FOREIGN KEY FK_22E4C69D9C7E259C');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A4889EDD2');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6494889EDD2');
        $this->addSql('DROP TABLE medecin');
        $this->addSql('DROP TABLE medecin_moyen_paiement');
        $this->addSql('DROP TABLE moyen_paiement');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE `user`');
    }
}
