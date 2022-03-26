<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303215759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actualite (id_actualite INT AUTO_INCREMENT NOT NULL, categorie INT DEFAULT NULL, titre_actualite VARCHAR(30) NOT NULL, description VARCHAR(255) NOT NULL, INDEX categorie (categorie), PRIMARY KEY(id_actualite)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_actualite (id_cat_actualite INT AUTO_INCREMENT NOT NULL, nom_cat_actualite VARCHAR(100) NOT NULL, PRIMARY KEY(id_cat_actualite)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_produit (id_cat_prod INT AUTO_INCREMENT NOT NULL, nom_cat_prod VARCHAR(30) NOT NULL, PRIMARY KEY(id_cat_prod)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id_commande INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_ligne INT DEFAULT NULL, INDEX id_ligne (id_ligne), INDEX id_user (id_user), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id_event INT AUTO_INCREMENT NOT NULL, nom_event VARCHAR(100) NOT NULL, date DATE NOT NULL, lieu VARCHAR(100) NOT NULL, desc_event VARCHAR(255) NOT NULL, PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id_ligne INT AUTO_INCREMENT NOT NULL, id_produit INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, PRIMARY KEY(id_ligne)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id_produit INT AUTO_INCREMENT NOT NULL, promotion INT DEFAULT NULL, categorie INT DEFAULT NULL, nom_produit VARCHAR(100) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, rate INT NOT NULL, description VARCHAR(255) NOT NULL, INDEX categorie (categorie), INDEX promotion (promotion), PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id_promotion INT AUTO_INCREMENT NOT NULL, prix_promotion DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_promotion)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, typereclamations_id INT DEFAULT NULL, sujet_rec VARCHAR(255) NOT NULL, niveau INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CE60640492E9C8EC (typereclamations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id_reservation INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_evenement INT DEFAULT NULL, INDEX id_evenement (id_evenement), INDEX id_user (id_user), PRIMARY KEY(id_reservation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id_role INT AUTO_INCREMENT NOT NULL, role VARCHAR(60) NOT NULL, PRIMARY KEY(id_role)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typereclamations (id INT AUTO_INCREMENT NOT NULL, niveau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id_user INT AUTO_INCREMENT NOT NULL, role INT DEFAULT NULL, reclamation_id INT DEFAULT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, email VARCHAR(60) NOT NULL, mdp VARCHAR(60) NOT NULL, adresse VARCHAR(60) DEFAULT NULL, code_postale INT DEFAULT NULL, num_tel INT NOT NULL, INDEX IDX_8D93D6492D6BA2D9 (reclamation_id), INDEX role (role), PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_54928197497DD634 FOREIGN KEY (categorie) REFERENCES categorie_actualite (id_cat_actualite)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB9759AB3 FOREIGN KEY (id_ligne) REFERENCES ligne_commande (id_ligne)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C11D7DD1 FOREIGN KEY (promotion) REFERENCES promotion (id_promotion)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27497DD634 FOREIGN KEY (categorie) REFERENCES categorie_produit (id_cat_prod)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640492E9C8EC FOREIGN KEY (typereclamations_id) REFERENCES typereclamations (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id_event)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64957698A6A FOREIGN KEY (role) REFERENCES role (id_role)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actualite DROP FOREIGN KEY FK_54928197497DD634');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27497DD634');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558B13D439');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB9759AB3');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C11D7DD1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492D6BA2D9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64957698A6A');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640492E9C8EC');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE categorie_actualite');
        $this->addSql('DROP TABLE categorie_produit');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE typereclamations');
        $this->addSql('DROP TABLE user');
    }
}
