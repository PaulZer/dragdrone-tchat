<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        if(in_array('ROLE_ADMIN',  $user->getRoles())){
            //tchat possible avec tous les utilisateurs
            $users = $this->getDoctrine()->getRepository('App\Entity\User')->findAll();

            //chat impossible avec soi-même
            unset($users[array_search($user, $users)]);
        } else {
            //tchat possible avec les admins
            $users = $this->getDoctrine()->getRepository('App\Entity\User')->findByRole("ROLE_ADMIN");
        }

        return $this->render('home.html.twig', [
            'isAdmin' => $this->isGranted('ROLE_ADMIN'),
            'users' => $users
        ]);
    }
}
