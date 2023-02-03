<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }
    #[Route('/', name: 'app_main')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

        //$lastUsername = $authenticationUtils->getLastUsername();
        //$lastUsername = $user->getEmail();

        //dd($user);

        if($user == null){
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }else{
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'lastUsername' => $user->getFullname()
            ]);
        }
        //dd($user);
        
    }
}
