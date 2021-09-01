<?php

namespace App\Controller;

use App\Entity\UserDetails;
use App\Form\UpdateProfileType;
use App\Form\UploadEventType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form  = $this->createForm(UpdateProfileType::class);
        $form -> handleRequest($request);

        $ajDi = $this->getUser()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $update = $entityManager->getRepository(UserDetails::class)->findOneBy(['user' => $ajDi]);

        if($form->isSubmitted() && $form->isValid()) {
            if($this->getUser()){
                if($update instanceof UserDetails){
                    try{
                        $update->setName($form->get('name')->getData());
                        $update->setSurname($form->get('surname')->getData());
                        $update->setCountry($form->get('country')->getData());
                        $update->setCity($form->get('city')->getData());
                        $update->setAge($form->get('age')->getData());
                        $update->setFavouriteSport($form->get('favourite_sport')->getData());

                        $entityManager -> persist($update);
                        $entityManager -> flush();

                        $this->addFlash('success', 'You succesfully updated your user details data');
                    }catch (\Exception $e){
                        $this->addFlash('error', 'An error occured while trying to update your data');
                    }
                }
                else{
                    try{
                        $entityUserDetails = new UserDetails();
                        $entityUserDetails->setUser($this->getUser());
                        $entityUserDetails->setName($form->get('name')->getData());
                        $entityUserDetails->setSurname($form->get('surname')->getData());
                        $entityUserDetails->setCountry($form->get('country')->getData());
                        $entityUserDetails->setCity($form->get('city')->getData());
                        $entityUserDetails->setAge($form->get('age')->getData());
                        $entityUserDetails->setFavouriteSport($form->get('favourite_sport')->getData());
                        $entityManager -> persist($entityUserDetails);
                        $entityManager -> flush();

                        $this->addFlash('success', 'You succesfully filled your user details ');
                    }catch (\Exception $e){
                        $this->addFlash('error', 'An error occured while trying to fill your data for first time');
                    }
                }
            }
            else{
                $this->addFlash('error', 'You are not logged in or its not tour account');
            }
        }

        $allDetails = $entityManager->getRepository(UserDetails::class)->findAll();

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $form->createView(),
            'allDetails' => $allDetails
        ]);
    }
}
