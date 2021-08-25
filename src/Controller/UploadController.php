<?php

namespace App\Controller;

use App\Form\UploadEventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/add", name="add_event")
     */
    public function upload(): Response
    {
        $form  = $this->createForm(UploadEventType::class);

        return $this->render('upload/upload.html.twig', [
            'uploadForm' => $form->createView(),
        ]);
    }
}
