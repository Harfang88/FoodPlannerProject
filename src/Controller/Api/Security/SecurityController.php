<?php

namespace App\Controller\Api\Security;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("api/login", name="login")
     */
    public function login(Request $request)
    {

        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        // header('Access-Control-Allow-DataType: *');

        // Dit au navigateur que la réponse est au format JSON
        header('Content-Type: application/json; charset=UTF-8');

        return $this->json([
            'data' => 'ok'
        ]);
    }


    /**
     * @Route("/registration", name="registration")
     */
    public function registration(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, RoleRepository $roleRepo, ValidatorInterface $validator)
    {

        $data = json_decode($request->getContent(), true);

        $newsLetter = $data['newsLetter'];
        $gender = $data['gender'];

        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);
        $form->submit($data);

        $role = $roleRepo->findOneBy(['name' => 'membre']);
        
        $hash = $encoder->encodePassword($user, $user->getPassword());
        
        $user->setPassword($hash)
        ->setRole($role)
        ->setGender($gender)
        ->setNewsLetter($newsLetter);
        
        $errors = $validator->validate($user);
        $errorArray = [];

        foreach( $errors as $error ) {
            $errorArray[] = $error->getMessage();
        }
        
        if(count($errorArray) > 0 ) {

        header("Access-Control-Allow-Origin: *");
            return new JsonResponse([
                'errors' => $errorArray,
            ]);
        }

        $em->persist($user);
        $em->flush();

        header("Access-Control-Allow-Origin: *");
        return new JsonResponse([
            'message' => 'Compte crée avec succès !'
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}
}
