<?php

namespace App\Controller\Backend\Blog;

use App\Utils\Slugger;
use App\Entity\Category;
use App\Form\BackendCategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backend/blog/category", name="backend_blog_category_", methods={"POST", "GET"})
 */
class CategoryController extends AbstractController
{
     /**
     * @Route("/{id}/delete", name="delete", requirements={"id"="\d+"}, methods="DELETE")
     */
    public function delete(Request $request, Category $category)
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('backend_blog_category_index');
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     * @Route("/{id}/edit", name="edit", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function add(Request $request, Category $category = null)
    {
        if( $category == null) {
            $category = new Category();
            $flashMessage = 'Catégorie ajoutée';
        }

        $flashMessage = 'Catégorie modifiée';
        $form = $this->createForm(BackendCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slugger = new Slugger();
            $category->setSlug($slugger->slugger($category->getName()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();


            $this->addFlash(
                'success',
                $flashMessage
            );
            
            return $this->redirectToRoute('backend_blog_category_index');
        }
        
        return $this->render('backend/blog/category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}/show", name="show", requirements={"id"="\d+"}, methods="GET")
     */
    public function show(Category $category)
    {
        $articles = $category->getArticles();
        
        return $this->render('backend/blog/category/show.html.twig', [
            'category' => $category,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(CategoryRepository $categoryRepo)
    {
        $categories = $categoryRepo->findAll();

        return $this->render('backend/blog/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
