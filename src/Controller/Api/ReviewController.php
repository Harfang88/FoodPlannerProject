<?php

namespace App\Controller\Api;

use DateTime;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{

    /**
     * @Route("/api/review/add", name="api_review_new", methods="POST")
     * @Route("/api/review/edit", name="api_review_edit", methods="PATCH")
     */
    public function add(Request $request, ArticleRepository $repo, EntityManagerInterface $em, ReviewRepository $reviewRepo)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();
        $article = $repo->find($data['id']);

        $review = new Review();

        $review->setBody($data['comment'])
               ->setCreatedAt(new DateTime())
               ->setArticle($article)
               ->setAuthor($user)
               ->setIsBlocked(false);

        $em->persist($review);

        $em->flush();
                
        return new JsonResponse([
            'message' => 'Commentaire ajouté avec succès',
            ]);

    }


    /**
     * @Route("/api/review/delete", name="api_review_delete", methods="DELETE")
     */
    public function delete(Request $request, ReviewRepository $reviewRepo, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        
        $review = $reviewRepo->find($data['id']);

        $em->remove($review);
        $em->flush();

        return new JsonResponse([
            'message' => 'Commentaire effacé avec succès',
            ]);
        
    }
}
