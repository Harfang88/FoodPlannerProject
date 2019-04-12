<?php

namespace App\Controller\Api;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    
    /**
     * @Route("/api/profil/favoris/add", name="api_user_favoris_add", methods="POST")
     */
    public function favAdd(Request $request, RecipeRepository $recipeRepo)
    {
        // Format de données accepté pour l'ajout en favoris : {"0":"id de la recette"}
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();

        $recipe = $recipeRepo->find(intval($data[0]));
        $favorisToAdd = $user->addFavori($recipe);

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        
        return new JsonResponse([
            'message' => 'Favoris ajouté',
        ]); 

    }

    /**
     * @Route("/api/profil/favoris/delete", name="api_user_favoris_delete", methods="DELETE")
     */
    public function favDelete(Request $request, RecipeRepository $recipeRepo)
    {
        // Format de données accepté pour la suppression de favoris : {"0":"id de la recette"}
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();

        $recipe = $recipeRepo->find(intval($data[0]));
        $favorisToDelete = $user->removeFavori($recipe);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse([
            'message' => 'Favoris supprimé',
        ]); 
    }

    /**
     * @Route("/api/profil/edit", name="api_profil_edit", methods="PATCH")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //Recupère l'envoie du front :
        $data = json_decode($request->getContent(), true);
        //User en cours :
        $user = $this->getUser();
        
        //Données user en court, en BDD avant traitement :
        $oldUsername = $user->getUsername();
        $oldPassword = $user->getPassword();
        $oldNewsLetter = $user->getNewsLetter();
        $oldGender = $user->getGender();
        $oldEmail = $user->getEmail();

        //Non modifiable ici :
        $role = $user->getRole();
 
        // Si aucune données reçues :
        if(empty($data)) {

            return new JsonResponse([
                'message' => 'Une erreur est survenue',
            ]); 
        }

        // Newsletter false/true :
        if($data['newsLetter'] == false) {
            $newsLetter = false;
        } elseif($data['newsLetter'] == true){
            $newsLetter = true;
        }

        // Si données recues vides :
        // Password
        if(empty($data['password'])) {
            $data['password'] = $oldPassword;
        }

        // Gender :
        if(empty($data['gender'])) {
            $data['gender'] = $oldGender;
        }

        // Email :
        if(empty($data['email'])) {
            $data['email'] = $oldEmail;
        }


        //Simulation form symfo, set nouvelles données user :
        $form = $this->createForm(RegistrationType::class, $user);
        $form->submit($data);


        if($form->isSubmitted()) {
            //Username fixe -> token :
            $username = $oldUsername;

            // Password
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            
            // Gender :
            $gender = $user->getGender();

            // Email :
            $email = $user->getEmail();
  
            $user->setUsername($username)
                ->setPassword($password)
                ->setNewsLetter($newsLetter)
                ->setGender($gender)
                ->setEmail($email)
                ->setRole($role);
        
            //Sauvegarde BDD :
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return new JsonResponse([
            'message' => 'Données mises à jour',
        ]);

    }

    /**
     * @Route("/api/user/status", name="api_user_status", methods="GET")
     */
    public function status()
    {
        $user = $this->getUser();
        $status = $user->getIsBlocked();

        return $this->json([
            'isBlocked' => $status
        ]);
    }

    /**
     * @Route("/api/profil/favoris", name="api_user_favoris", methods="GET")
     */
    public function favIndex()
    {
        $user = $this->getUser();
        $allFavoris = $user->getFavoris();
        $recipeFav = [];


        foreach($allFavoris as $favoris) {
            $recipeFav[$favoris->getId()] = [
                'title' => $favoris->getTitle(),
                'image' => $favoris->getPicture(),
            ];
        }
        
        return $this->json([
            'favoris' => $recipeFav,
        ]);

    }

    /**
     * @Route("/api/profil", name="api_profil_index", methods="GET")
     */
    public function show()
    {
        $user = $this->getUser();

        return $this->json($user, 200, [], [
            'groups' => ['profil'],
        ]);
    }

}
