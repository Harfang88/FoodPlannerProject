<?php

namespace App\Controller\Api;

use DateTime;
use App\Entity\Etape;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RecipeIngredientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RecipeController extends AbstractController
{
    /**
    * @Route("/recipe", name="index", methods="GET")
    */
    public function index(RecipeRepository $repo)
    {
        $recipes = $repo->findAll();

        return $this->json($recipes, 200, [], [
        'groups' => ['recipes'],
        ]);
    }


    /**
     * @Route("/api/recipe/add", name="api_recipe_add", methods="POST")
     * @Route("/api/recipe/edit", name="api_recipe_edit", methods="PATCH")
     */
    public function add(Request $request, TypeRepository $typerepo, EntityManagerInterface $em,  RecipeRepository $recipeRepo)
    {
        //Recupère l'envoie du front :
        $data = json_decode($request->getContent(), true);

        $recipe = $recipeRepo->find($data['id']);


        if(!$recipe) {
            $recipe = new Recipe();
        }

        $user = $this->getUser();
        $type = $typerepo->findOneBy(['name' => 'Entrée']);

        $recipe->setTitle('recette de luxe')
                ->setCalorie(330)
                ->setDifficulty(4)
                ->setTime(60)
                ->setPicture('https://images.unsplash.com/photo-1458644267420-66bc8a5f21e4?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1493&q=80')
                ->setUser($user)
                ->setType($type)
                ->setCreatedAt(new DateTime())
                ->setValidated(false);

                for($i = 1; $i < 4; $i++) {

                    $etape = new Etape();

                    $etape->setDescription('description de l\'etape')
                          ->setEtapeOrder($i);
                    $em->persist($etape);

                    $recipe->addEtape($etape);
                    $em->persist($recipe);
                }

                $em->flush();

                return new JsonResponse([
                    'message' => 'Recette ajoutée',
                ]);
    }


    /**
    * @Route("/recipe/show", name="show", methods="POST")
    */
    public function show(RecipeRepository $repo)
    {
        $data = json_decode($request->getContent(), true);

        $recipe = $repo->findBy(['slug' => $data]);

        return $this->json($recipes, 200, [], [
        'groups' => ['recipes'],
        ]);
    }


}
