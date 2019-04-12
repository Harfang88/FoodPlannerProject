<?php

namespace App\Controller\Backend\Blog;

use App\Utils\Slugger;
use App\Entity\Article;
use App\Form\BackendArticleType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backend/blog/article", name="backend_blog_article_")
*/
class ArticleController extends AbstractController
{
    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function add(Request $request, UserRepository $userRepo, Article $article = null)
    {
        if( $article == null) {
            $article = new Article();
            $flashMessage = 'Article ajouté';
        }

        $user = $this->getUser();
        $flashMessage = 'Article modifié';
        $form = $this->createForm(BackendArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slugger = new Slugger();
            $article->setSlug($slugger->slugger($article->getTitle()));

            $article->setUser($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();


            $this->addFlash(
                'success',
                $flashMessage
            );
            
            return $this->redirectToRoute('backend_blog_article_index');
        }
        
        return $this->render('backend/blog/article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods="DELETE", requirements={"id"="\d+"})
     */
    public function delete(Request $request, Article $article)
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            $this->addFlash(
                'danger',
                'L\'article a été supprimé avec succès'
            );
        }

        return $this->redirectToRoute('backend_blog_article_index');
    }

     /**
     * @Route("/{id}/show", name="show", methods="GET", requirements={"id"="\d+"})
     */
    public function show(Article $article)
    {
        return $this->render('backend/blog/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("", name="index", methods="GET")
     */
    public function index(ArticleRepository $articleRepo, PaginatorInterface $paginator, Request $request)
    {
        $articles = $articleRepo->findAllCustom();
        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('backend/blog/article/index.html.twig', [
            'articles' => $pagination,
        ]);
    }


}
