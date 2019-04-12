<?php

namespace App\Controller\Backend\Blog;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backend/blog", name="backend_blog_review_")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/review", name="index", methods="GET")
     */
    public function index(ReviewRepository $reviewRepo, PaginatorInterface $paginator, Request $request)
    {
        $reviews = $reviewRepo->reviewsByDate();
        $pagination = $paginator->paginate(
            $reviews, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('backend/blog/review/index.html.twig', [
            'reviews' => $pagination,
        ]);
    }


    /**
     * @Route("/review/{id}/block", name="block", methods="GET")
     */
    public function block(Review $review)
    {

        //Status blocage en BDD :
        $oldStatus = $review->getIsBlocked();        


        //Inversement du status au clic :
        if($oldStatus == true) {
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        //Set en bdd :
        $review->setIsBlocked($newStatus);
        $em = $this->getDoctrine()->getManager();
        $em->flush();


        return $this->json([
            'status' => 'ok',
            'message' => 'Review is block/unblock'
        ]);
    }
}

