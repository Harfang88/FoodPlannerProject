<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShoppingListRepository;
use App\Entity\ShoppingList;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlanningRepository;
use Symfony\Component\HttpFoundation\Response;
use Fpdf\Fpdf;
use App\Utils\ShoppingListGenerator;
use App\Utils\ShoppingListPdfGenerator;


/**
 * @Route("api/shopping/list", name="api_shopping_list_")
 */
class ShoppingListController extends AbstractController
{
    /**
     * @Route("", name="show", methods ="POST")
     */
    public function show(ShoppingListGenerator $shoppingListGenerator, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $from = new \DateTime($data['startDate'] );
        $to = new \DateTime($data['endDate']);

        $userId = $this->getUser()->getId();

        $shoppingList = $shoppingListGenerator->generator($from, $to, $userId);

            return $this->json([
                'status' => 'ok',
                'data' => $shoppingList
            ]);
    }



    /**
     * @Route("/pdf", name="pdf", methods ="POST")
     */
    public function pdf(ShoppingListGenerator $shoppingListGenerator, ShoppingListPdfGenerator $pdfGenetator, Request $request)
    {
        // Récupère les données de la requette
        $data = json_decode($request->getContent(), true);

        // On crée des objets Datetime
        $from = new \DateTime($data['startDate'] );
        $to = new \DateTime($data['endDate']);
        // $this->getUser()->getId();
        $userId = 23;
        
        // On vérifie si les ingrédients du tableau on étaient modifiés
        $listFromBdd = $shoppingListGenerator->generator($from, $to, $userId);
        $listFromData = $data['updatedList'];

        if( md5(serialize($listFromBdd)) != md5(serialize($listFromData)) ) {
            $list = $listFromData;
        } else {
            $list = $listFromBdd;
        }

        $rootPath = $this->getParameter('kernel.project_dir');
      
        $pdf = $pdfGenetator->generator($list, $rootPath);
        header("Access-Control-Allow-Origin: *");

        return new Response($pdf->Output(), 200, ['Content-Type' => 'application/pdf']);
    }
}
