<?php

namespace App\Controller\Api;

use DateTime;
use Exception;
use App\Entity\Planning;
use App\Utils\PlanningEdition;
use App\Utils\PlanningGenerator;
use App\Repository\RecipeRepository;
use App\Repository\PlanningRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanningController extends AbstractController
{
    /**
     * @Route("api/planning", name="api_planning_show", methods="POST")
     */
    public function show(PlanningRepository $planningRepo, Request $request)
    {
        //Données recues du Front :
        $data = json_decode($request->getContent(), true);

        // On crée des objets Datetime
        $from = new \DateTime($data['startDate'] );
        $to = new \DateTime($data['endDate']);

        $user = $this->getUser();

        $plannings = $planningRepo->findPlanningByUser($from, $to, $user->getId());

        $planningGenerator = new PlanningGenerator();
        $weekDays = $planningGenerator->generator($plannings);
        
        
        return $this->json([
            'status' => 'ok<3',
            'data' => $weekDays
        ]);
    }

    /**
     * @Route("api/planning/edit", name="api_planning_edit", methods="PUT")
     */
    public function edit(Request $request, PlanningEdition $planningEdition)
    {
        //Données recues du Front :
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();

        $planningEdition->edition($data, $user);


        return $this->json([
            'message' => 'Modifications sauvegardées',
        ]);
    }

}
