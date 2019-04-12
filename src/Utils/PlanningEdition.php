<?php

namespace App\Utils;

use DateTime;
use App\Entity\Planning;
use App\Repository\RecipeRepository;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;


class PlanningEdition
{

    private $planningRepo;
    private $em;
    private $recipeRepo;

    public function __construct(PlanningRepository $planningRepo, RecipeRepository $recipeRepo,EntityManagerInterface $em)
    {
        $this->planningRepo = $planningRepo;
        $this->recipeRepo = $recipeRepo;
        $this->em = $em;
    }

    public function edition($data, $user)
    {
         // Correspondance jour/date en français.
         $localDays = [
            'Lundi'    => date('Y-m-d', strtotime($data['startDate'])),
            'Mardi'    => date('Y-m-d', strtotime($data['startDate'] . '+ 1 days')),
            'Mercredi' => date('Y-m-d', strtotime($data['startDate'] . '+ 2 days')),
            'Jeudi'    => date('Y-m-d', strtotime($data['startDate'] . '+ 3 days')),
            'Vendredi' => date('Y-m-d', strtotime($data['startDate'] . '+ 4 days')),
            'Samedi'   => date('Y-m-d', strtotime($data['startDate'] . '+ 5 days')),
            'Dimanche' => date('Y-m-d', strtotime($data['startDate'] . '+ 6 days')),
        ];


        // Récupère le tableau(7) des plannings par jour :
        foreach($data['planning'] as $day => $plannings) {

            //Récupère le jour + deux tableaux(14-14) avec repas et recettes dans un tableau(7):
            foreach($plannings as $meal => $planning) {
                

                $recipeTitle = $planning['recipeTitle'];
                $planningId = $planning['planningId'];
                

                //A supprimer :
                if(empty($recipeTitle) && !empty($planningId)) {

                    $planningById = $this->planningRepo->find($planning['planningId']);

                    $this->em->remove($planningById);
                    $this->em->flush();
               
                //A updater :
                } elseif(!empty($recipeTitle) && !empty($planningId)) {

                    $planningById = $this->planningRepo->find($planning['planningId']);
                    $recipe = $this->recipeRepo->findOneBy(['title' => $recipeTitle]);

                    $planningById->setRecipe($recipe);
                    $this->em->persist($planningById);
                    $this->em->flush();

                // A créer:
                } elseif(!empty($recipeTitle) && empty($planningId)) {

                    $recipe = $this->recipeRepo->findOneBy(['title' => $recipeTitle]);

                    $newPlanning = new Planning();
                    $newPlanning->setUser($user)
                                ->setRecipe($recipe)
                                ->setMealTime($meal)
                                ->setMealday(new DateTime($localDays[$day]));
                    $this->em->persist($newPlanning);
                    $this->em->flush();
                }
            }
        }

    }
   

}