<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Type;
use App\Entity\Etape;
use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use App\Entity\Planning;
use App\Entity\Comment;
use App\Entity\Note;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Review;


class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
         // initialisation de l'objet Faker
        $faker = Factory::create('fr_FR');

        //On crée des roles
        $roleUser = new Role();
        $roleUser->setCode('ROLE_USER')
                 ->setName('Membre');
        $manager->persist($roleUser);

        $roleManager = new Role();
        $roleManager->setCode('ROLE_MANAGER')
                    ->setName('Content manager');
        $manager->persist($roleManager);

        $roleAdmin = new Role();
        $roleAdmin->setCode('ROLE_ADMIN')
                  ->setName('Administrateur');
        $manager->persist($roleAdmin);


        //On crée des utilisateurs (Membres et un Admin)
        $users = [''];

        for( $i = 0; $i <= 2; $i++ ) {

            $users[$i] = new User();

            $password = $this->encoder->encodePassword($users[$i], 'toto');
            $gender = ['Femme', 'Homme', 'Autre'];

            $users[$i]->setEmail($faker->safeEmail)
                     ->setPassword($password)
                     ->setGender($gender[mt_rand(0,2)]);

            if( $i == 0 ) {
                $users[$i]->setRole($roleAdmin)
                         ->setUsername('Admin');
            } else {
                $users[$i]->setRole($roleUser)
                ->setUsername($faker->firstName()); 
            }

            $manager->persist($users[$i]);
        }

        //on crée des types de repas
        $types = [''];
        for( $i = 0; $i <= 2 ; $i++ ) {

            $types[$i] = new Type();

            if( $i == 0 ) {
                $types[$i]->setName('Entrée');
            } elseif ( $i == 1 ) {
                $types[$i]->setName('Plat');
            } else {
                $types[$i]->setName('Dessert');
            }
            $manager->persist($types[$i]);
        }

        //On crée des recettes
        $recipes = [''];
        for( $i = 0; $i <= 25; $i++ ) {

            $recipes[$i] = new Recipe();
            $recipes[$i]->setTitle(ucfirst(join(' ' , $faker->words(4, false))))
                       ->setCalorie($faker->numberBetween(100, 750))
                       ->setDifficulty(mt_rand(1,5))
                       ->setTime(date_time_set(new DateTime(),mt_rand(0,2),mt_rand(0,59),0,0))
                       ->setPicture($faker->imageUrl(800, 600, 'food'))
                       ->setCreatedAt($faker->dateTimeBetween('-1 year'))
                       ->setvalidated(true)
                       ->setType($types[mt_rand(0,2)])
                       ->setUser($users[mt_rand(0,1)]);

            //Ajoute des ingredients
            
            for( $k = 0; $k <= mt_rand(5, 15); $k++ ) {

                $ingredient = new Ingredient();
                $ingredient->setName(ucfirst($faker->word))
                            ->setUnit('unité')
                            ->setQuantity($faker->numberBetween(1, 5))
                            ->setRecipe($recipes[$i]);

                $manager->persist($ingredient);
            }

            $manager->persist($recipes[$i]);

            //On ajoute les étapes
            for( $j = 1; $j <= mt_rand(3, 8); $j++) {

                $etape = new Etape();
                $etape->setDescription(join(' ', $faker->paragraphs(3,false)))
                      ->setEtapeOrder($j)
                      ->setRecipe($recipes[$i]);

                      $manager->persist($etape);

            }

            // On ajoute des commentaires aux recettes
            for( $j = 0; $j <= mt_rand(0,5); $j++) {

                $comment = new Comment();
                
                // Pour éviter d'avoir une date de création de commentaire antérieur à la date de création de la recette
                $days = (new \DateTime())->diff($recipes[$i]->getCreatedAt())->days; 

                $comment->setBody( join($faker->paragraphs(mt_rand(1,4),false)) )
                        ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))
                        ->setRecipe($recipes[$i])
                        ->setAuthor($users[mt_rand(0,1)])
                        ->setIsBlocked(false);
                
                $manager->persist($comment);
            }

        }

        //On boucle sur les Users pour les ajouter des relations
        for( $j = 0; $j <= 2; $j++ ) {
            
            //On ajoute des favories aux utilisateurs
            for( $i = 0; $i <= mt_rand(0,1); $i++ ) {
                
                $users[$j]->addFavori($recipes[mt_rand(0, 24)]);

                $manager->persist($users[$j]);
            }

            //On ajoute des repas au planning des utilisateurs
            $counter = 0;
            $date = '2019-04-01';

            for( $i = 0; $i <= 13 ; $i++) {

                $counter += 1;

                $planning = new Planning();
                $planning->setMealDay($faker->dateTimeBetween('-4 week'))
                         ->setRecipe($recipes[mt_rand(0,25)])
                         ->setUser($users[$j]);

                if( ($i % 2) != 0) {
                    $planning->setMealTime('Déjeuner');
                } else {
                    $planning->setMealTime('Diner');
                }

                if ( $counter == 3) {
                    $date = date('Y-m-d', strtotime($date. ' + 1 days'));
                    $counter = 1;
                }

                $planning->setMealDay( new \DateTime($date));

                
                $manager->persist($planning);
            }

            // On ajoute des notes aux recettes
            for( $f = 0; $f <= 25; $f++) {
                $note = new Note();
                $note->setNote(mt_rand(0,5))
                        ->setUser($users[$j])
                        ->setRecipe($recipes[$f]);

                $manager->persist($note);
            }

        }

        //******** BLOG *********/

        //on crée des catégories
        $category = [];
        for( $i = 0; $i <= 5; $i++) {

            $category[$i]= new Category();

            $category[$i]->setName(ucfirst($faker->word));

            $manager->persist($category[$i]);
        }

        //On crée des articles
        for( $i = 0; $i < 10; $i++) {

            $article = new Article();

            $article->setTitle($faker->sentence(6))
                    ->setContent( join(' ', $faker->paragraphs(4)) )
                    ->setCreatedAt($faker->dateTimeBetween('-4 week'))
                    ->setImage($faker->imageUrl(800, 600, 'food'))
                    ->setUser($users[0]);

            for( $j = 0; $j < mt_rand(1,3); $j++) {
                $article->addCategory($category[mt_rand(0,5)]);
            }

            //On crée des reviews pour chaque articles
            for( $k = 0; $k < mt_rand(2, 6) ; $k++) {

                $review = new Review();

                // Pour éviter d'avoir une date de création de review antérieur à la date de création de l'article
                $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                $review->setBody( join(' ', $faker->paragraphs(mt_rand(1,2))) )
                       ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))
                       ->setAuthor($users[mt_rand(0,2)])
                       ->setArticle($article)
                       ->setIsBlocked(false);
                
                $manager->persist($review);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }
}
