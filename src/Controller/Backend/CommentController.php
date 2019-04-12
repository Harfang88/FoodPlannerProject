<?php

namespace App\Controller\Backend;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/backend/comment", name="backend_comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(CommentRepository $commentRepo, PaginatorInterface $paginator, Request $request)
    {
        $comments = $commentRepo->commentsByDate();

        $pagination = $paginator->paginate(
            $comments, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('backend/comment/index.html.twig', [
            'comments' => $pagination,
        ]);
    }

    /**
     * @Route("/comment/{id}/block", name="block", methods={"GET"})
     */
    public function block(Comment $comment)
    {

        //Status blocage en BDD :
        $oldStatus = $comment->getIsBlocked();
        

        //Inversement du status au clic :
        if($oldStatus == true) {
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        //Set en bdd :
        $comment->setIsBlocked($newStatus);
        $em = $this->getDoctrine()->getManager();
        $em->flush();


        // return $this->redirectToRoute('backend_comment_index');

        return $this->json([
            'status' => 'ok',
            'message' => 'Comment is block/unblock'
        ]);
    }
}