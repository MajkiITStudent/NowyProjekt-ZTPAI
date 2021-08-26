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
     * @Route("/profile/{id}", name="profile")
     * @param int $id
     * @return Response
     */
    public function index(int $id, Request $request): Response
    {
        $form  = $this->createForm(UpdateProfileType::class);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if($this->getUser()){
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

                    $this->addFlash('success', 'You succesfully updated your user details data');
                }catch (\Exception $e){
                    $this->addFlash('error', 'An error occured while trying to update your data');
                }
            }else{
                $this->addFlash('error', 'You are not logged in or its not tour account');
            }
        }

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
