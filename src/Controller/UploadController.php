<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\UploadEventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/add", name="add_event")
     */
    public function upload(Request $request): Response
    {
        $form  = $this->createForm(UploadEventType::class);

        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();

            if($this->getUser()){
                $entityEvent = new Event();
                $entityEvent->setUser($this->getUser());
                $entityEvent->setFilename($form->get('filename')->getData());
                $entityEvent->setUploadedAt(new \DateTime());
                $entityEvent->setDescription($form->get('description')->getData());
                $entityEvent->setSportType($form->get('sport_type')->getData());
                $entityEvent->setEventDatetime($form->get('event_datetime')->getData());
                $entityEvent->setPeopleNeeded($form->get('people_needed')->getData());

                $entityManager -> persist($entityEvent);
                $entityManager -> flush();
            }
        }

        return $this->render('upload/upload.html.twig', [
            'uploadForm' => $form->createView(),
        ]);
    }
}
