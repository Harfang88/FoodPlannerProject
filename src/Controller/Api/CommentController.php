<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class CommentController extends AbstractController
{
    /**
     * @Route("/api/comment/add", name="api_comment_add", methods="POST")
     */

    public function add(RecipeRepository $recipeRepo, EntityManagerInterface $em, UserRepository $userRepo, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $recipe = $recipeRepo->find($data['id']);

        $comment = new Comment();
        $user = $this->getUser();
        
        $comment->setAuthor($user)
                ->setRecipe($recipe)
                ->setBody($data['comment'])
                ->setIsBlocked(false);
                
     
        $em->persist($comment);
        $em->flush();
        
        return new JsonResponse([
            'message' => 'Commentaire ajouté',
        ]);
    }

    /**
     * @Route("/api/comment/delete", name="api_comment_delete", methods="DELETE")
     */
    public function delete(CommentRepository $repo, EntityManagerInterface $em, Request $request)
    {
        //Recupère l'envoie du front :
        $data = json_decode($request->getContent(), true);

        $comment = $repo->find($data['id']);
        
        $em->remove($comment);
        $em->flush();
    }

    /**
     * @Route("api/comment/show", name="api_comment_show", methods="POST")
     */
    public function show(Request $request, CommentRepository $commentRepo, RecipeRepository $recipeRepo)
    {
        $data = json_decode($request->getContent(), true);
        $recipe = $recipeRepo->find(intval($data));
        $comments = $commentRepo->findUnblockedComment($recipe);

        return $this->json([
            'data' => $comments
        ]);
    }
}
