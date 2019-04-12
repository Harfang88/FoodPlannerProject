<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\BackendUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/backend/user/block/{id}", name="backend_user_block", methods="GET")
     */
    public function block(User $user)
    {
        //Status blocage en BDD :
        $oldStatus = $user->getIsBlocked();

        //Inversement du status au clic :
        if($oldStatus == true) {
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        //Set en bdd :
        $user->setIsBlocked($newStatus);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        // return $this->redirectToRoute('backend_user_index');

        return $this->json([
            'status' => 'ok',
            'message' => 'User is block/unblock'
        ]);
    }

    /**
     * @Route("/backend/user/{id}/role", name="backend_user_role", methods={"POST", "GET"})
     */
    public function editRole(Request $request, User $user)
    {
        //Formulaire et auto-set :
        $form = $this->createForm(BackendUserType::class, $user);
        $form->handleRequest($request);

        // Si soumission et données valides (POST):
        if ($form->isSubmitted() && $form->isValid()) {
           $em = $this->getDoctrine()->getManager();

           //Sauvegarde en BDD :
           $em->flush();

           //On set un flash message
           $this->addFlash(
            'success',
            'Role modifié avec succès'
        );

           return $this->redirectToRoute('backend_user_index');
        }
        //Si formulaire non soumis:
        return $this->render('backend/user/roleEdit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/backend/user/", name="backend_user_index", methods="GET")
     */
    public function index(UserRepository $userRepo)
    {
        $users = $userRepo->findAll();
        return $this->render('backend/user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
