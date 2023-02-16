<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\UserService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_main');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserRepository $userRepository,
    UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager,
    UrlGeneratorInterface $urlGenerator): Response
    {
        $form = $this->createFormBuilder()
                ->add('email', EmailType::class ,[
                    'required' => true,
                ])
                ->add('pseudo' ,TextType::class,[
                    'required' => true,
                ])
                ->add('firstname' ,TextType::class,[
                    'required' => true,
                ])
                ->add('lastname' ,TextType::class)
                ->add('password', PasswordType::class,[
                    'required' => true,
                ])
                ->add('confirmpassword', PasswordType::class,[
                    'required' => true,
                ])
                ->getForm();

        $form->handleRequest($request);

        $flash ="";
        $pwd_error ="";

        if ($form->isSubmitted() && $form->isValid()) {

            ///data user.
            $data = $form->getData();

            //$this->mailService->valid_email($data['email']);

            ///check the email if already exist
            if ($userRepository->findOneBy(['email' => $data['email']])) {
                // dd("Email already exist.");

                $flash = "Cette adresse email déjà exist.";
            }else if($data['password'] != $data['confirmpassword']){
                $pwd_error = 'Les deux mots de passe doit être identique!';
            }else{

                $user = new User();
                $user->setPseudo(trim($data['pseudo']));
                $user->setEmail(trim($data['email']));
                $user->setPassword($data['password']);
                $user->setFirstname($data['firstname']);
                $user->setLastname($data['lastname']);

                ////setting roles for user admin.
                if( count($userRepository->findAll()) === 0 ){
                    $user->setRoles(["ROLE_GODMODE"]);
                }else{
                    $user->setRoles(["ROLE_USER"]);
                }

                ///hash password
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);

                $user->setIsVerified(true);

                $entityManager->persist($user);
                $entityManager->flush();

                $user_id = $userRepository->findOneBy(['email' => $data['email']])->getId();

                //dd( $user_id);
                $user_service =  new UserService();

                $user->setTablemessage("tb_message_".$user_id);

                $user_service ->createTablemessage("tb_message_".$user_id);
                
                $user->setTablenotification("tb_notification_".$user_id);

                $user_service ->createTablenotification("tb_notification_".$user_id);

                $user->setTablecarte("tb_carte_".$user_id);

                $user_service ->createTablecarte("tb_carte_".$user_id);

                $user->setTablepublication("tb_publication_".$user_id);

                $user_service ->createTablepublication("tb_publication_".$user_id);

                $user->setTableactivity("tb_activity_".$user_id);

                $user_service ->createTableactivity("tb_activity_".$user_id);

                $user->setTablefriends("tb_friends_".$user_id);

                $user_service ->createTablefriends("tb_friends_".$user_id);

                $entityManager->flush();

                return new RedirectResponse($urlGenerator->generate('app_login'));

            }
            
        }
    
        

        return $this->render('registration/register.html.twig',
            ['form'=> $form->createView(),
            'pwd_error' => $pwd_error,
            'email_error'=> $flash
        ]);
    }
    #[Route(path: '/story', name: 'app_story')]
    public function story()
    {
        return $this->render('story.html.twig');
    }
}
