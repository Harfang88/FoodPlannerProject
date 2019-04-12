<?php

namespace App\Controller\Backend;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Form\IngredientType;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Ingredient;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/backend/recipe", name="backend_recipe_")
     */
class RecipeController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"POST", "GET"})
     */
    public function index(RecipeRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $recipes = $repo->findAllCustom();

        $pagination = $paginator->paginate(
        $recipes, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        10 /*limit per page*/
    );
        
        return $this->render('backend/recipe/index.html.twig', [
            'recipes' => $pagination,
        ]);
    }

    /**
     * @Route("/add", name="new", methods={"GET","POST"}),
     * @Route("/edit/{id}", name="edit", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function new(Recipe $recipe = null, Request $request, EntityManagerInterface $em, UserRepository $userRepo)
    {
        $flashMessage = 'Recette modifié avec succes';

        if( $recipe == null) {
            $recipe = new Recipe();
            $flashMessage = 'Recette créé avec succes';
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {


            $recipe->setUser($this->getUser())// En attendat que la connexion soit gérée
                   ->setValidated(true)
                   ->setPublishable(false)
            ;

            //On set les étapes
            foreach( $recipe->getEtapes() as $etape ) {
                $etape->setRecipe($recipe);
            }
            
            // On set les ingrédients
            foreach( $recipe->getIngredients() as $ingredient ) {
                $ingredient->setRecipe($recipe);
            }

            // On envoi en base
            $em->persist($recipe);
            $em->flush();

            $this->addFlash(
                'success',
                $flashMessage
            );

            return $this->redirectToRoute('backend_recipe_index');
        }

        return $this->render('backend/recipe/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id"="\d+"}, methods ="DELETE")
     */
    public function delete(Recipe $recipe, EntityManagerInterface $em, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {

            $em->remove($recipe);
            $em->flush();

            $this->addFlash(
                'danger',
                'La recette a été supprimé avec succès'
            );
        }
        return $this->redirectToRoute('backend_recipe_index');
    }

    /**
     * @Route("/validate/{id}", name="validate", requirements={"id"="\d+"}, methods ="GET")
     */
    public function validate(Recipe $recipe, EntityManagerInterface $em, Request $request)
    {

        $recipe->setValidated(true)
               ->setPublishable(false);

        $em->persist($recipe);
        $em->flush();

        $this->addFlash(
            'primary',
            'La recette a été publié avec succès'
        );

        return $this->redirectToRoute('backend_recipe_index');
    }

}
