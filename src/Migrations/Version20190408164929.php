<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408164929 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, object_id VARCHAR(255) DEFAULT NULL, INDEX IDX_23A0E66A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_category (article_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_53A4EDAA7294869C (article_id), INDEX IDX_53A4EDAA12469DE2 (category_id), PRIMARY KEY(article_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, article_id INT DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_blocked TINYINT(1) NOT NULL, INDEX IDX_794381C6F675F31B (author_id), INDEX IDX_794381C67294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, recipe_id INT DEFAULT NULL, author_id INT NOT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_blocked TINYINT(1) NOT NULL, INDEX IDX_9474526C59D8A214 (recipe_id), INDEX IDX_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, calorie INT NOT NULL, slug VARCHAR(255) NOT NULL, difficulty INT NOT NULL, time TIME NOT NULL, picture VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, publishable TINYINT(1) DEFAULT NULL, validated TINYINT(1) NOT NULL, object_id VARCHAR(255) DEFAULT NULL, INDEX IDX_DA88B137C54C8C93 (type_id), INDEX IDX_DA88B137A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, username VARCHAR(191) NOT NULL, password VARCHAR(255) NOT NULL, news_letter TINYINT(1) DEFAULT NULL, gender VARCHAR(255) NOT NULL, email VARCHAR(191) NOT NULL, is_blocked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_recipe (user_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_BFDAAA0AA76ED395 (user_id), INDEX IDX_BFDAAA0A59D8A214 (recipe_id), PRIMARY KEY(user_id, recipe_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, user_id INT NOT NULL, note INT NOT NULL, INDEX IDX_CFBDFA1459D8A214 (recipe_id), INDEX IDX_CFBDFA14A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(255) NOT NULL, quantity INT NOT NULL, INDEX IDX_6BAF787059D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_intolerance (ingredient_id INT NOT NULL, intolerance_id INT NOT NULL, INDEX IDX_B62113ED933FE08C (ingredient_id), INDEX IDX_B62113ED844A8E38 (intolerance_id), PRIMARY KEY(ingredient_id, intolerance_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, user_id INT NOT NULL, meal_day DATETIME NOT NULL, meal_time VARCHAR(255) NOT NULL, INDEX IDX_D499BFF659D8A214 (recipe_id), INDEX IDX_D499BFF6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, description LONGTEXT NOT NULL, etape_order INT NOT NULL, INDEX IDX_285F75DD59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intolerance (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_category ADD CONSTRAINT FK_53A4EDAA7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_category ADD CONSTRAINT FK_53A4EDAA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C67294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_recipe ADD CONSTRAINT FK_BFDAAA0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_recipe ADD CONSTRAINT FK_BFDAAA0A59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1459D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_intolerance ADD CONSTRAINT FK_B62113ED933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_intolerance ADD CONSTRAINT FK_B62113ED844A8E38 FOREIGN KEY (intolerance_id) REFERENCES intolerance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF659D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_category DROP FOREIGN KEY FK_53A4EDAA7294869C');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C67294869C');
        $this->addSql('ALTER TABLE article_category DROP FOREIGN KEY FK_53A4EDAA12469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C59D8A214');
        $this->addSql('ALTER TABLE user_recipe DROP FOREIGN KEY FK_BFDAAA0A59D8A214');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1459D8A214');
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787059D8A214');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF659D8A214');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD59D8A214');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395');
        $this->addSql('ALTER TABLE user_recipe DROP FOREIGN KEY FK_BFDAAA0AA76ED395');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A76ED395');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137C54C8C93');
        $this->addSql('ALTER TABLE ingredient_intolerance DROP FOREIGN KEY FK_B62113ED933FE08C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE ingredient_intolerance DROP FOREIGN KEY FK_B62113ED844A8E38');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_recipe');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_intolerance');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE intolerance');
    }
}
