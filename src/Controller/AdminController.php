<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    /**
     * @Route("/adminPanel", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $allEvents = $entityManager->getRepository(Event::class)->findAll();
        $allUsers = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/admin.html.twig', [
            'allEvents' => $allEvents,
            'allUsers' => $allUsers
        ]);
    }
}
