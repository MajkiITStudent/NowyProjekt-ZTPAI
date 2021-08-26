<?php

namespace App\Controller;

use App\Entity\Event;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
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
            $this->addFlash('error','You must be logged in to do perform action');
        }

        return $this->redirectToRoute("show_events");
    }

    /**
     * @Route("/show/remove/{id}", name="remove_event")
     * @param int $id
     * @return RedirectResponse
     */
    public function removeEvent(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $eventToRemove = $entityManager->getRepository(Event::class)->find($id);

        if($this->getUser() && $this->getUser() == $eventToRemove->getUser()){
            try{
                $fileToRemove = new Filesystem();
                $fileToRemove->remove('assets/images/events/'.$eventToRemove->getFilename());
                if($fileToRemove->exists('assets/images/events/'.$eventToRemove->getFilename())){
                    $this->addFlash('error','We could not manage your order to remove this event');
                }else{
                    $entityManager->remove($eventToRemove);
                    $entityManager->flush();
                    $this->addFlash('success','You succesfully deleted your event');
                }
            }catch (Exception $e){
                $this->addFlash('error','Something go wrong :(');
            }
        }else{
            $this->addFlash('error','You are logged out or you are not owner of this event');
        }
        return $this->redirectToRoute("show_events");
    }
}
