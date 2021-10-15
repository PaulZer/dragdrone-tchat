<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class TchatHistoryController extends AbstractController
{
    /**
     * @Route("/tchatHistory", name="tchat_history")
     */
    public function index(): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        return $this->render('tchat/tchatHistory.html.twig', [
            'user' => $user,
            'isAdmin' => $this->isGranted('ROLE_ADMIN'),
            'users' => $users
        ]);
    }
}
