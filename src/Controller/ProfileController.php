<?php

namespace App\Controller;

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

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
