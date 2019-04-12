<?php

namespace App\Controller\Api;

use App\Entity\Note;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class NoteController extends AbstractController
{
    /**
     * @Route("/api/note/add", name="api_note_add", methods="POST")
     */
    public function add(RecipeRepository $recipeRepo, EntityManagerInterface $em, UserRepository $userRepo, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $recipe = $recipeRepo->find($data['id']);

        $note = new Note();
        $user = $this->getUser();
        
        $note->setNote(mt_rand(1, 5))
             ->setUser($user)
             ->setRecipe($recipe);
        
        $em->persist($note);
        $em->flush();
        
        return new JsonResponse([
            'message' => 'Note bien ajoutée',
        ]);

    }

    /**
     * @Route("/note", name="api_note_show", methods="POST")
     */
    public function show(Request $request, RecipeRepository $recipeRepo, NoteRepository $repoNote)
    {
        $notes = $repoNote->findNoteCustom();

        $data = json_decode($request->getContent(), true);
        
        $notesByRecipe = [];


        foreach($notes as $note) {
           if(array_key_exists($note['id'], $notesByRecipe)) {
                $recipeId = $note['id'];
                // $notesByRecipe[$note['id']] = [$note['note']];
                array_push($notesByRecipe[$recipeId], $note['note']);
            } else {
                $notesByRecipe[$note['id']] = [];
                $notesByRecipe[$note['id']] = [$note['note']];
            }
        }
        foreach($notesByRecipe as $key => $noteByRecipe) {
            $total = array_sum($noteByRecipe) / count($noteByRecipe);
            $total = intval(round($total, 0, PHP_ROUND_HALF_DOWN));
            unset($notesByRecipe[$key]);
            $notesByRecipe[$key]= $total;
        }
        
        return $this->json([
            'data' => $notesByRecipe
        ]);
    }
}