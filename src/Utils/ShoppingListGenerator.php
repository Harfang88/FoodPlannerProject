<?php

namespace App\Utils;


use App\Entity\Planning;
use App\Repository\PlanningRepository;


class ShoppingListGenerator 
{
    private $repo;

    public function __construct(PlanningRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Array[]
     * List of ingredients with quantity and unit
     */
    public function generator($from, $to, $userId)
    {
        $plannings = $this->repo->findPlanningBetweenDate($from, $to, $userId);
        $ingredientList = [];
        $arrayIngredient = [];

        // Recettes pour un planning :
        foreach( $plannings as $planning) {
            $recipe = $planning->getRecipe();
            $ingredients = $recipe->getIngredients();

            foreach( $ingredients as $ingredient) {

                //On récupére le nom des ingrédients
                $ingredientName = $ingredient->getName();
                $ingredientQuantity = $ingredient->getQuantity();
                $ingredientUnit = $ingredient->getUnit();

                // On set les valeurs dans un tableau
                if(array_key_exists($ingredientName, $arrayIngredient)) {
                    $arrayIngredient[$ingredientName]['quantity'] += intval($ingredientQuantity);
                } else {
                    $arrayIngredient[$ingredientName] = ['quantity' => intval($ingredientQuantity), 'unit' => $ingredientUnit];
                }
            }
        }
        return $arrayIngredient;
    }
}