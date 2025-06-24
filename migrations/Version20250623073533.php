<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250623073533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE formateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE formation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, actif_formation BOOLEAN NOT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, date_debut_validation DATE DEFAULT NULL, date_fin_validation DATE DEFAULT NULL, titre_professionnel VARCHAR(255) NOT NULL, niveau INTEGER NOT NULL, nombre_stagiaires INTEGER DEFAULT NULL, groupe_rattachement VARCHAR(255) NOT NULL, nombre_heures INTEGER DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE formation_formateur (formation_id INTEGER NOT NULL, formateur_id INTEGER NOT NULL, PRIMARY KEY(formation_id, formateur_id), CONSTRAINT FK_270B2E925200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_270B2E92155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_270B2E925200282E ON formation_formateur (formation_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_270B2E92155D8F51 ON formation_formateur (formateur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE interruption (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, formation_id INTEGER NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, CONSTRAINT FK_F9511BC05200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F9511BC05200282E ON interruption (formation_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE invitation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, used BOOLEAN NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jour_ferie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, nom VARCHAR(255) NOT NULL, zone VARCHAR(50) NOT NULL, annee INTEGER DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE period_en_entreprise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, formation_id INTEGER NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, numbre_heures INTEGER DEFAULT NULL, CONSTRAINT FK_92E223D25200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_92E223D25200282E ON period_en_entreprise (formation_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, codepostal VARCHAR(5) NOT NULL, ville VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, date_invitation DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , password VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_497B315EE7927C74 ON utilisateurs (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE formateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE formation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE formation_formateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE interruption
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jour_ferie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE period_en_entreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateurs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
