<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class TchatHistoryController extends AbstractController
{
    /**
     * @Route("/tchatHistory/from/{fromUserId}/to/{toUserId}", name="tchat_history")
     */
    public function index(int $fromUserId, int $toUserId): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $tchatMessages = $this->getDoctrine()->getRepository('App\Entity\TchatMessage')->findTchatHistory($fromUserId, $toUserId);

        return $this->render('tchat/tchatHistory.html.twig', [
            'toUser' => $this->getDoctrine()->getRepository('App\Entity\User')->find($toUserId),
            'tchatMessages' => $tchatMessages
        ]);
    }
}
