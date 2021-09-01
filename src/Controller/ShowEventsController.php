<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventParticipants;
use App\Entity\UserDetails;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ShowEventsController extends AbstractController
{
    /**
     * @Route("/show", name="show_events", methods={"GET"})
     */
    public function showEvents(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $allEvents = $entityManager->getRepository(Event::class)->findAllUpcomingEvents();
        $completeEvents = $entityManager->getRepository(Event::class)->findAllCompleteEvents();
        $pastEvents = $entityManager->getRepository(Event::class)->findAllPastEvents();

        return $this->render('show_events/showEvents.html.twig', [
            'allEvents' => $allEvents,
            'completeEvents' => $completeEvents,
            'pastEvents' => $pastEvents
        ]);
    }

    /**
     * @Route("/showMy", name="my_events", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showMyEvents(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $myEvents = $entityManager->getRepository(Event::class)->findBy(['user' => $this->getUser()]);

        return $this->render('show_events/myEvents.html.twig', [
            'myEvents' => $myEvents
        ]);
    }

    /**
     * @Route("/show/take_part/{id}", name="take_part", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param int $id
     * @return RedirectResponse
     */
    public function takePart(int $id): RedirectResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $takePartAtEvent = $entityManager->getRepository(Event::class)->find($id);
        $ifExists = $entityManager->getRepository(EventParticipants::class)->findOneBy(['event' => $id]);
        $howMany = $takePartAtEvent->getPeopleNeeded();

        if($this->getUser() != $takePartAtEvent->getUser() && $howMany > 0){
            if($ifExists instanceof EventParticipants){
                try{
                    $ifExists->addUser($this->getUser());
                    $takePartAtEvent->setPeopleNeeded($howMany -1);
                    $entityManager->persist($ifExists);
                    $entityManager->persist($takePartAtEvent);
                    $entityManager->flush();
                    $this->addFlash('success','Success, you will atend this event!');
                }catch(Exception $e){
                    $this->addFlash('error','Something go wrong :(');
                }
            }else{
                try{
                    $eventParticipants = new EventParticipants();
                    $eventParticipants->setEvent($takePartAtEvent);
                    $eventParticipants->addUser($this->getUser());
                    $takePartAtEvent->setPeopleNeeded($howMany -1);
                    $entityManager->persist($eventParticipants);
                    $entityManager->persist($takePartAtEvent);
                    $entityManager->flush();
                    $this->addFlash('success','Success, you will atend this event!');
                }catch(Exception $e){
                    $this->addFlash('error','Something go wrong :(');
                }
            }
        }else{
            $this->addFlash('error','You must be logged in / you cant take part in your own event or this event is complete');
        }

        return $this->redirectToRoute("show_events");
    }

    /**
     * @Route("/show/remove/{id}", name="remove_event", methods={"DELETE","GET"})
     * @IsGranted("ROLE_USER")
     * @param int $id
     * @return RedirectResponse
     */
    public function removeEvent(int $id): RedirectResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $eventToRemove = $entityManager->getRepository(Event::class)->find($id);

        if($this->getUser() == $eventToRemove->getUser() || $this->getUser()->getUserIdentifier() == 'admin'){
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

    /**
     * @Route("/show/details/{id}", name="show_details", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function authorDetails(int $id): Response{
        $entityManager = $this->getDoctrine()->getManager();
        $choosedEvent = $entityManager->getRepository(Event::class)->find($id);
        $authorID = $choosedEvent->getUser()->GetId();
        $authorDetails = $entityManager->getRepository(UserDetails::class)->findOneBy(['user' => $authorID]);
        $eventDescription = $choosedEvent->getDescription();
        $eventSportType = $choosedEvent->getSportType();
        $eventDateTime = $choosedEvent->getEventDateTime();
        $eventPeopleNeeded = $choosedEvent->getPeopleNeeded();
        $authorName = $authorDetails->getName();
        $authorSurname = $authorDetails->getSurname();
        $authorCountry = $authorDetails->getCountry();
        $authorCity = $authorDetails->getCity();
        $authorAge = $authorDetails->getAge();
        $authorFavouriteSport = $authorDetails->getFavouriteSport();

        return $this->render('profile/author.html.twig', [
            'eventDescription' => $eventDescription,
            'eventSportType' => $eventSportType,
            'eventDateTime' => $eventDateTime,
            'eventPeopleNeeded' => $eventPeopleNeeded,
            'authorName' => $authorName,
            'authorSurname' => $authorSurname,
            'authorCountry' => $authorCountry,
            'authorCity' => $authorCity,
            'authorAge' => $authorAge,
            'authorFavouriteSport' => $authorFavouriteSport

        ]);
    }

    /**
     * @Route("/showParticipants/{id}", name="event_participants", methods={"GET"})
     * @param int $id
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function showEventParticipants(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $eventParticipants = $entityManager->getRepository(EventParticipants::class)->findOneBy(['event' => $id]);
        $users = $eventParticipants->getUser();

        return $this->render('show_events/participants.html.twig', [
            'users' => $users
        ]);
    }

}
