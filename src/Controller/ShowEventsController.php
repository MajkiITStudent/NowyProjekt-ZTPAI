<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowEventsController extends AbstractController
{
    /**
     * @Route("/show", name="show_events")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $allEvents = $entityManager->getRepository(Event::class)->findAll();

        return $this->render('show_events/index.html.twig', [
            'allEvents' => $allEvents
        ]);
    }
}
