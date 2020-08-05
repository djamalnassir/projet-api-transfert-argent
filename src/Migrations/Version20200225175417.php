<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225175417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, compte_envoi_id INT NOT NULL, compte_retrait_id INT NOT NULL, user_envoi_id INT NOT NULL, user_retrait_id INT NOT NULL, montant_transction DOUBLE PRECISION NOT NULL, code VARCHAR(255) NOT NULL, client_recepteur VARCHAR(255) NOT NULL, client_emetteur VARCHAR(255) NOT NULL, tel_recepteur VARCHAR(255) NOT NULL, tel_emetteur VARCHAR(255) NOT NULL, frais INT NOT NULL, part_emetteur DOUBLE PRECISION NOT NULL, part_recepteur DOUBLE PRECISION NOT NULL, part_systeme DOUBLE PRECISION NOT NULL, part_etat DOUBLE PRECISION NOT NULL, cni_emetteur VARCHAR(255) NOT NULL, cni_recepteur VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, INDEX IDX_723705D1BD277F18 (compte_envoi_id), INDEX IDX_723705D1B6EC9AC4 (compte_retrait_id), INDEX IDX_723705D1DF1A08E5 (user_envoi_id), INDEX IDX_723705D1D99F8396 (user_retrait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1BD277F18 FOREIGN KEY (compte_envoi_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B6EC9AC4 FOREIGN KEY (compte_retrait_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1DF1A08E5 FOREIGN KEY (user_envoi_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D99F8396 FOREIGN KEY (user_retrait_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media_object CHANGE file_path file_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE image_id image_id INT DEFAULT NULL, CHANGE partenaire_id partenaire_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE transaction');
        $this->addSql('ALTER TABLE media_object CHANGE file_path file_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE image_id image_id INT DEFAULT NULL, CHANGE partenaire_id partenaire_id INT DEFAULT NULL');
    }
}
