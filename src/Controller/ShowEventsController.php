<?php

namespace App\Controller;

use App\Entity\Event;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowEventsController extends AbstractController
{
    /**
     * @Route("/show", name="show_events")
     */
    public function showEvents(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $allEvents = $entityManager->getRepository(Event::class)->findAll();

        return $this->render('show_events/showEvents.html.twig', [
            'allEvents' => $allEvents
        ]);
    }

    /**
     * @Route("/show/take_part/{id}", name="take_part")
     * @param int $id
     * @return RedirectResponse
     */
    public function takePart(int $id): RedirectResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $takePartAtEvent = $entityManager->getRepository(Event::class)->find($id);
        $howMany = $takePartAtEvent->getPeopleNeeded();

        if($this->getUser()){
            try{
                $takePartAtEvent->setPeopleNeeded($howMany -1);
                $entityManager->persist($takePartAtEvent);
                $entityManager->flush();
                $this->addFlash('success','Success, you will atend this event!');
            }catch(Exception $e){
                $this->addFlash('error','Something go wrong :(');
            }
        }else{
            $this->addFlash('error','You mus be logged in to do perform action');
        }

        return $this->redirectToRoute("show_events");
    }
}
