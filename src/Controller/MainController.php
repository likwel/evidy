<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    private $em;
    public function __construct(private UrlGeneratorInterface $urlGenerator, EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/', name: 'app_main')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

        //$lastUsername = $authenticationUtils->getLastUsername();
        //$lastUsername = $user->getEmail();

        $user_list = $this->em->getRepository(User::class)->findAll();

        //dd($user_list);

        if($user == null){
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }else{
            $fullname = $user->getFirstname().' '.$user->getLastname();

            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'lastUsername' => $fullname,
                'user' => $user,
                'user_list' =>$user_list
            ]);
        }
        //dd($user);
        
    }
}
