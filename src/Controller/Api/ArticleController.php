<?php

namespace App\Controller\Api;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="api_article", methods="GET")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->json($articles, 200, [], [
            'groups' => ['blog'],
        ]);
    }


     /**
     * @Route("/article/show", name="api_article_show", methods="POST")
     */
    public function show(Request $request, ArticleRepository $repo)
    {
        $data = json_decode($request->getContent(), true);
        
        $article = $repo->findOneBy(['slug' => $data]);

        return $this->json($article, 200, [], [
            'groups' => ['blog'],
        ]);
    }
}
