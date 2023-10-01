<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    //RENDER WELCOME PAGE TO LOGIN/SIGNUP
    #[Route('/', name: 'app_welcome')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home', ['targetUser' => $this->getUser()->getId()]);
        }

        return $this->render('welcome/index.html.twig', [

        ]);
    }
}
