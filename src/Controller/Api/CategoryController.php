<?php

namespace App\Controller\Api;


use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/api/category", name="api_category", methods="GET")
     */
    public function index(CategoryRepository $repo)
    {
        $categories = $repo->findAll();
        
        return $this->json($categories, 200, [], [
            'groups' => ['blog'],
            ]);
    }

}
