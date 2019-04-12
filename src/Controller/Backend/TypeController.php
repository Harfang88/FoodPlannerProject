<?php

namespace App\Controller\Backend;

use App\Entity\Type;
use App\Form\TypeType;
use App\Utils\Slugger;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/backend/type", name="backend_type_", methods={"POST", "GET"})
 */
class TypeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(TypeRepository $typeRepository)
    {
        return $this->render('backend/type/index.html.twig', [
            'types' => $typeRepository->findAll(),
        ]);
    }

     /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function add(Request $request, EntityManagerInterface $em, Type $type = null)
    {
        $flashMessage = 'Type modifié avec succés';

        if( $type == null ) {
            $type = new Type();
            $flashMessage = 'Type modifié avec succés';
        }

        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($type);
            $em->flush();

            $this->addFlash(
                'success',
                $flashMessage
            );

            return $this->redirectToRoute('backend_type_index');
        }

        return $this->render('backend/type/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
