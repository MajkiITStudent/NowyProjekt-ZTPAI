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
                /** @var  $eventImg */
                $eventImg = $form -> get('filename')->getData();
                if($eventImg){
                    $originalImgName = pathinfo($eventImg->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalImgName);
                    $nameToSave = $safeName.'_'.uniqid().'.'.$eventImg->guessExtension();
                    $eventImg->move('assets/images/events',$nameToSave);

                    $entityEvent = new Event();
                    $entityEvent->setUser($this->getUser());
                    $entityEvent->setFilename($nameToSave);
                    $entityEvent->setUploadedAt(new \DateTime());
                    $entityEvent->setDescription($form->get('description')->getData());
                    $entityEvent->setSportType($form->get('sport_type')->getData());
                    $entityEvent->setEventDatetime($form->get('event_datetime')->getData());
                    $entityEvent->setPeopleNeeded($form->get('people_needed')->getData());

                    $entityManager -> persist($entityEvent);
                    $entityManager -> flush();

                }
            }
        }

        return $this->render('upload/upload.html.twig', [
            'uploadForm' => $form->createView(),
        ]);
    }
}
