<?php

namespace App\Controller\Documentation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiDocumentationController extends AbstractController
{
    /**
     * @Route("/documentation", name="documentation", methods="GET")
     */
    public function index()
    {
        return $this->render('documentation/api_documentation/index.html.twig');
    }
}
