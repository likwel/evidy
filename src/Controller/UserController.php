<?php

namespace App\Controller;

use PDO;
use App\Entity\User;
use App\Service\PDOService;
use App\Service\UserService;
use App\Service\CarteService;
use App\Service\VenteService;
use App\Service\MessageService;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepo, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepo->findOneById(1);
        dd($user);

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/notifications', name: 'app_notifications')]
    public function getNotifications()
    {
        $notification = new NotificationService();

        $table_notif = $this->getUser()->getTablenotification();

        $nbr_nonLu = $notification ->getNotShow($table_notif);

        $response = new StreamedResponse();
        $response->setCallback(function () use (&$nbr_nonLu) {

            echo "data:" . json_encode($nbr_nonLu) .  "\n\n";
            ob_end_flush();
            flush();
        });
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;

    }
    #[Route('/user/messages', name: 'app_messages')]
    public function getMessages()
    {
        $message = new MessageService();

        $table_msg = $this->getUser()->getTablemessage();

        $nbr_nonLu = $message ->getNotShow($table_msg);

        //dd($nbr_nonLu);

        $response = new StreamedResponse();
        $response->setCallback(function () use (&$nbr_nonLu) {

            echo "data:" . json_encode($nbr_nonLu) .  "\n\n";
            ob_end_flush();
            flush();
        });
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;

    }

    #[Route('/user/cartes', name: 'app_cartes')]
    public function getCartes()
    {
        $carte = new CarteService();

        $table_carte = $this->getUser()->getTablecarte();

        $nbr_nonLu = $carte ->getNotWait($table_carte);

        //dd($nbr_nonLu);

        $response = new StreamedResponse();
        $response->setCallback(function () use (&$nbr_nonLu) {

            echo "data:" . json_encode($nbr_nonLu) .  "\n\n";
            ob_end_flush();
            flush();
        });
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;

    }

    #[Route('/user/get_messages', name: 'app_get_messages')]
    public function getAllMessage()
    {
        $message = new MessageService();

        $table_msg = $this->getUser()->getTablemessage();

        dd($table_msg);

        $messages = $message ->getAll($table_msg);
        if(count($messages)>0){
            return $this->json($messages);
        }else{
            return $this->json("Aucun message");
        }

    }

    #[Route('/user/get_messages_by_id/{user_id}', name: 'app_get_messages_by_id')]
    public function getAllMessageById($user_id)
    {
        $message = new MessageService();

        $table_msg = $this->getUser()->getTablemessage();

        $messages = $message ->getMessageById($table_msg,$user_id);

        $response = new StreamedResponse();
        
        //return $response;

        if(count($messages)>0){
            $response->setCallback(function () use (&$messages) {

                echo "data:" . json_encode($messages) .  "\n\n";
                ob_end_flush();
                flush();
            });
            
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
            $response->headers->set('Cache-Control', 'no-cache');
            $response->headers->set('Content-Type', 'text/event-stream');
            return $response;
        }else{
            return $this->json("Aucun message");
        }

    }

    #[Route('/user/setIsShow', name: 'app_setIsShow')]
    public function setIsShow()
    {
        $message = new MessageService();

        $table_msg = $this->getUser()->getTablemessage();

        $message ->setIsShow($table_msg);

        return $this->json("Message vu");

    }

    #[Route('/user/setIsRead/{user_id}', name: 'app_setIsRead')]
    public function setIsRead($user_id)
    {
        $message = new MessageService();

        $table_msg = $this->getUser()->getTablemessage();

        $message ->setIsRead($table_msg,$user_id);

        return $this->json("Message lu");

    }

    #[Route('/user/setIsShow_notif', name: 'app_setIsShow_notif')]
    public function setIsShow_notif()
    {
        $message = new NotificationService();

        $table_msg = $this->getUser()->getTablenotification();

        $message ->setIsShow($table_msg);

        return $this->json("Notification vu");

    }

    #[Route('/user/setIsRead_notif/{user_id}', name: 'app_setIsRead_notif')]
    public function setIsRead_notif($user_id)
    {
        $message = new NotificationService();

        $table_msg = $this->getUser()->getTablenotification();

        $message ->setIsRead($table_msg,$user_id);

        return $this->json("Notification lu");

    }

    /**
     * @Route("user/send_message" , name="app_send_message")
     */
    public function sendMessage(Request $request)
    {
        $user = $this->getUser();

        $requestContent = json_decode($request->getContent(), true);

        $user_id = $requestContent["user_id"];
        $content = $requestContent["content"];

        $message = new MessageService();

        $table_msg = $this->getUser()->getTablemessage();

        $other_user = $this->em->getRepository(User::class)->findOneById($user_id);

        $tbl_msg_other_user = $other_user->getTablemessage();

        $message ->sendOneMessage($table_msg, $user_id, $content, 1);

        $message ->sendOneMessage($tbl_msg_other_user, $user->getId(), $content, 0);

        return  $this->json("Message envoyé");
    }
    #[Route('/user/all_messages', name: 'app_messenger')]
    public function getMessageUser(Request $request): Response
    {
        $user = $this->getUser();

        $table_msg = $this->getUser()->getTablemessage();

        $msg_serv = new MessageService();

        $user_list = $this->em->getRepository(User::class)->findAll();

        $last_msg = null;

        $all_message = null;

        $other_user = null;

        if($msg_serv ->getLastMessage($table_msg)){
            $last_msg = $msg_serv ->getLastMessage($table_msg);
            $all_message =$msg_serv->getMessageById($table_msg, $last_msg["user_id"]);
            $other_user = $this->em->getRepository(User::class)->findOneById($last_msg["user_id"]);
        }

        $table_msg = array();

        foreach($all_message as $message){
            array_push($table_msg, ["message"=>$message,"user"=>$this->em->getRepository(User::class)->findOneById($message["user_id"])]);
        }

        //dd( $table_msg);

        return $this->render('user/message.html.twig', [
            'user'=>$user,
            'user_list' =>$user_list,
            'last_message' =>$last_msg,
            'all_message' => $table_msg,
            'other_user' => $other_user,
        ]);
    }

    #[Route('/user/messages_by', name: 'app_messenger_user_id')]
    public function getMessageAll(): Response
    {
        $user = $this->getUser();

        $table_msg = $this->getUser()->getTablemessage();

        $msg_serv = new MessageService();

        $user_serv = new UserService();

        //$all_message =$msg_serv->getMessageById($table_msg,$user_id);
        $all_message = $msg_serv->getAll($table_msg);

        $table_msg = array();

        foreach($all_message as $message){
            array_push($table_msg, ["message"=>$message,"user"=>$user_serv-> getUserById($message["user_id"])]);
        }

        $response = new StreamedResponse();
        
        //return $response;
        //dd($table_msg);

        if(count($table_msg)>0){
            $response->setCallback(function () use (&$table_msg) {

                echo "data:" . json_encode($table_msg) .  "\n\n";
                ob_end_flush();
                flush();
            });
            
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
            $response->headers->set('Cache-Control', 'no-cache');
            $response->headers->set('Content-Type', 'text/event-stream');
            return $response;
            //return  $this->json($all_message);
        }else{
            return $this->json("Aucun message");
        }

    }
    #[Route('/user/post_sale', name: 'app_post_sale')]
    public function postVente(Request $request): Response
    {
        $user = $this->getUser();

        $table_vente = $this->getUser()->getTableactivity();

        $vente_serv = new VenteService();

        $data = json_decode($request->getContent(), true);

        
        $path = $this->getParameter('kernel.project_dir') . '/public/uploads/post/'; 

        $product = $data["product"];
        $description = $data["description"];
        $devise= $data["devise"];
        $location = $data["location"]; 
        $user_id = $data["user_id"];
        $price = $data["price"];
        $quantity = $data["quantity"];
        $photos= $data["photos"];
        $isDelivery= $data["isDelivery"];
        $isWait = $data["isWait"];

        $photos_name = array();

        if(count($photos) > 0 ){

            foreach( $photos as $photo ){
                // Function to write image into file
                $temp = explode(";", $photo );
                $extension = explode("/", $temp[0])[1];

                $image_name = "post_image_". time() . uniqid(). '.'.$extension;

                file_put_contents($path ."/". $image_name, file_get_contents($photo));
                
                array_push($photos_name,$image_name);

            }
        }

        $vente_serv->publierVente($table_vente, $product, $description, $devise, $location, $user_id, $price, $quantity, json_encode($photos_name), $isDelivery, $isWait);
        //return new RedirectResponse($this->urlGenerator->generate('app_main'));
        return  $this->json("Success");
        
    }

    #[Route('/user/get_sale', name: 'app_get_sale')]
    public function getVente(Request $request): Response
    {
        $user = $this->getUser();

        $table_vente = $this->getUser()->getTableactivity();

        $vente_serv = new VenteService();

        $data = $vente_serv->getAll($table_vente);
        if(count($data)>0){
            return  $this->json($data);
        }else{
            return  $this->json("Aucun post");
        }
        
    }

    #[Route('/user/add_friend', name: 'app_add_friend')]
    public function addFriend(Request $request): Response
    {
        $user = $this->getUser();

        $my_table_friend = $this->getUser()->getTablefriends();

        $data = json_decode($request->getContent(), true);

        $user_id = $data["user_id"];
        /*$isFollow = 1;
        $isWait = 0;*/

        $other_user = $this->em->getRepository(User::class)->findOneById($user_id);

        $user_serv = new UserService();

        $user_serv->addFriend($my_table_friend,$user_id, 0, 1);

        $table_friend_other_user = $other_user->getTablefriends();

        $user_serv->addFriend($table_friend_other_user,$this->getUser()->getId(), 1, 1);

        //send notification

        $notif_serv = new NotificationService();

        $content = $user->getFirstname()." ".$user->getLastname()." vous envoyé une invitation d'amis";

        $type ="Demande d'amis";

        $tb_notif = 'tb_notification_'.$user_id;

        $notif_serv->sendOneNotification($tb_notif, $content,$user->getId(), $type);
        
        return  $this->json("Success");
        
    }
    #[Route('/user/friends', name: 'app_friends')]
    public function getFriends(): Response
    {
        $user = $this->getUser();

        $fullname = $user->getFirstname().' '.$user->getLastname();

        $user_table_friend = $user->getTablefriends();

        $user_tab_activity = $user->getTableactivity();

        $user_serv = new UserService();

        $friend_list = $user_serv ->getAllFriends($user_table_friend);

        $tab_friend =array();

        foreach($friend_list as $friend){
            array_push($friend, $this->em->getRepository(User::class)->findOneById($friend["user_id"]));
            array_push($tab_friend,$friend);
        }

        $activity_serv =  new VenteService();
        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

        //dd($tab_friend);

        return $this->render('user/friends.html.twig', [
            'user'=>$user,
            'friends'=>$tab_friend,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number
        ]);

    }

    #[Route('/user/accept_invitation/{user_id}', name: 'app_accept_invitation')]
    public function acceptFriend($user_id): Response
    {
        $user = $this->getUser();
        $user_serv = new UserService();
        $user_serv->acceptFriend($user->getTablefriends(), $user_id, $user->getId());
        
        //Send notification

        $notif_serv = new NotificationService();

        $content = $user->getFirstname()." ".$user->getLastname()."  a accepté votre invitation d'amis";

        $type ="Invitation";

        $tb_notif = 'tb_notification_'.$user_id;

        $notif_serv->sendOneNotification($tb_notif, $content,$user->getId(), $type);

        return $this->json("Success");
    }
    
    #[Route('/user/delete_friend/{user_id}', name: 'app_delete_friend')]
    public function deleteFriend($user_id): Response
    {
        $user = $this->getUser();
        $user_serv = new UserService();
        $user_serv->deleteFriend($user->getTablefriends(), $user_id, $user->getId());

        return $this->json("Success");
    }

    #[Route('/user/abonnements', name: 'app_abonnements')]
    public function getSubcription(): Response
    {
        $user = $this->getUser();

        $fullname = $user->getFirstname().' '.$user->getLastname();

        $user_table_friend = $user->getTablefriends();

        $user_tab_activity = $user->getTableactivity();

        $user_serv = new UserService();

        $friend_list = $user_serv ->getAllFriends($user_table_friend);

        $tab_friend =array();

        foreach($friend_list as $friend){
            array_push($friend, $this->em->getRepository(User::class)->findOneById($friend["user_id"]));
            array_push($tab_friend,$friend);
        }

        $activity_serv =  new VenteService();
        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

        //dd($tab_friend);

        return $this->render('user/subsciption.html.twig', [
            'user'=>$user,
            'friends'=>$tab_friend,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number
        ]);

    }

    #[Route('/user/parametres', name: 'app_parametres')]
    public function parametres(Request $request, UserRepository $userRepository, 
    EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        $fullname = $user->getFirstname().' '.$user->getLastname();

        $user_table_friend = $user->getTablefriends();

        $user_tab_activity = $user->getTableactivity();

        $user_serv = new UserService();

        $activity_serv =  new VenteService();
        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);


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
        ->getForm();

        $form2 = $this->createFormBuilder()
                ->add('new_password', PasswordType::class,[
                    'required' => true,
                ])
                ->add('old_password', PasswordType::class,[
                    'required' => true,
                ])
        ->getForm();
        
        //$form = $this->createForm($form, $post[0]);

        $form->handleRequest($request);

        $flash ="";

        if ($form->isSubmitted() && $form->isValid()) {

            ///data user.
            $data = $form->getData();
            $user->setPseudo(trim($data['pseudo']));
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);

            $entityManager->flush();

            $flash ="Succes";
        }

        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {

            ///data user.
            $data2 = $form2->getData();
            /*$user->setPseudo(trim($data['pseudo']));
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);*/

            if ($passwordEncoder->isPasswordValid($user, $data2["old_password"])){

                $password = $passwordEncoder->hashPassword($user, $data2["new_password"]);

                $user->setPassword($password);

                $entityManager->flush();

                $flash ="PasswordSucces";

            }else{
                $flash ="PasswordError";
            }

        }

        //dd($tab_friend);

        return $this->render('user/parametres.html.twig', [
            'form'=> $form->createView(),
            'form2'=> $form2->createView(),
            'user'=>$user,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number,
            'flash' => $flash
        ]);

    }

    #[Route('/user/panier', name: 'app_panier')]
    public function getAllCartes(): Response
    {
        $user = $this->getUser();

        $fullname = $user->getFirstname().' '.$user->getLastname();

        $user_table_friend = $user->getTablefriends();

        $user_tab_activity = $user->getTableactivity();

        $user_tab_carte = $user->getTablecarte();

        $user_serv = new UserService();

        $carte_serv = new CarteService();
        
        $activity_serv =  new VenteService();

        $all_carte = $carte_serv ->getAll($user_tab_carte);

        //dd($user);

        $tab_carte =array();

        foreach($all_carte as $carte){
            array_push($carte, $this->em->getRepository(User::class)->findOneById($carte["user_id"]),$activity_serv->getOneByUser($carte["product_id"],$carte["user_id"]));
            array_push($tab_carte,$carte);
        }

        //dd($tab_carte );
        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

        //dd($tab_friend);

        return $this->render('user/cartes.html.twig', [
            'user'=>$user,
            'cartes'=>$tab_carte,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number
        ]);

    }
    
    #[Route('/user/get_notifications', name : 'app_get_notifications')]
    public function get_notifications(Request $request): Response
    {
        $user = $this->getUser();
        $notif_serv = new NotificationService();

        $tab_notif = $user->getTablenotification();

        $res = $notif_serv->getAll($tab_notif);

        if(count($res)>0){
            return $this->json($res);
        }else{
            return $this->json("Null");
        }

    }

    #[Route('/user/update_card', name: 'app_update_card')]
    public function updateCard(Request $request): Response
    {
        $user = $this->getUser();

        $requestContent = json_decode($request->getContent(), true);

        $id = $requestContent["id"];
        $quantity = $requestContent["quantity"];

        //$user_serv = new UserService();

        $table_carte = $user->getTablecarte();
        //$user_serv->deleteFriend($user->getTablefriends(), $user_id, $user->getId());

        $carte_serv =  new CarteService();

        $carte_serv->updateCard($table_carte, $quantity, $id);

        return $this->json("Carte à jours");
    }

    #[Route('/user/get_product_by/{id}/{user_id}', name : 'app_get_getOneProduct')]
    public function getOneProduct($id,$user_id): Response
    {
        $user = $this->getUser();

        $carte_serv =  new CarteService();

        $res = $carte_serv->getById($id, $user_id);

        return $this->json($res);

    }

    #[Route('/user/add_to_card', name: 'app_add_to_card')]
    public function addToCard(Request $request): Response
    {
        $user = $this->getUser();

        $my_table_card = $this->getUser()->getTablecarte();

        $data = json_decode($request->getContent(), true);

        $user_id = $data["user_id"];
        $product_id = $data["product_id"];
        $quantity = $data["quantity"];

        $carte_serv = new CarteService();

        $carte_serv->addOneCard($user->getTablecarte(), $product_id, $user_id, $quantity);

        $notif_serv = new NotificationService();

        $content = $user->getFirstname()." ".$user->getLastname()." a commandé votre annonce";

        $type ="Carte";

        $tb_notif = 'tb_notification_'.$user_id;

        $notif_serv->sendOneNotification($tb_notif, $content,$user->getId(), $type);
        
        return  $this->json("Success");
        
    }

    #[Route('/user/remove_to_card/{id}', name: 'app_remove_to_card')]
    public function removeToCard(Request $request, $id): Response
    {
        $user = $this->getUser();

        $carte_serv = new CarteService();

        $carte_serv->removeToCarte($user->getTablecarte(),$id);
        
        return  $this->json("Success");
        
    }

    #[Route('/user/set_is_vendu/{id}', name: 'app_set_is_vendu')]
    public function setIsVendu($id): Response
    {
        $user = $this->getUser();

        $vente_serv = new VenteService();

        $vente_serv->setIsVendu($user->getTableactivity(),$id);
        
        return  $this->json("Success");
        
    }

    #[Route('/user/profil/{id}', name: 'app_profil')]
    public function profil($id): Response
    {
        $user = $this->getUser();

        $user_serv = new UserService();

        $carte_serv = new CarteService();
        
        $activity_serv =  new VenteService();

        $profil = $this->em->getRepository(User::class)->findOneById($id);

        //dd($profil);

        $user_table_friend = $profil->getTablefriends();

        $user_tab_activity = $profil->getTableactivity();

        $fullname = $profil->getFirstname().' '.$profil->getLastname();

        $all_activity = $activity_serv->getAll($user_tab_activity);

        //dd($all_activity);

        $isFriend = $user_serv->isFriend($user->getTablefriends(), $id);

        $positionFriend = $user_serv->positionFriend($user->getTablefriends(), $id);

        //dd($isFriend);

        $number_friend = $user_serv->getNumberFriends($user_table_friend);

        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);
        
        return $this->render('user/profil.html.twig', [
            'user'=>$profil,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number,
            'profil'=> $profil,
            'products'=>$all_activity,
            'isFriend'=>$isFriend,
            'position_friend'=>$positionFriend,
            'nb_friend'=>$number_friend
        ]);
        
    }

    #[Route('/user/profil/{user_id}/detail/{id}', name: 'app_product_detail')]
    public function Productdetail($user_id, $id): Response
    {
        $user = $this->getUser();

        $user_serv = new UserService();

        $carte_serv = new CarteService();
        
        $activity_serv =  new VenteService();

        $profil = $this->em->getRepository(User::class)->findOneById($user_id);

        //dd($profil);

        $user_table_friend = $profil->getTablefriends();

        $user_tab_activity = $profil->getTableactivity();

        $fullname = $profil->getFirstname().' '.$profil->getLastname();

        $activity = $activity_serv->getOneByUser($id, $user_id);

        $isFriend = $user_serv->isFriend($user->getTablefriends(), $user_id);

        //dd($activity);
        $number_friend = $user_serv->getNumberFriends($user_table_friend);

        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);
        
        return $this->render('user/productDetail.html.twig', [
            'user'=>$profil,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number,
            'profil'=> $profil,
            'product'=>$activity,
            'isFriend'=>$isFriend,
            'nb_friend'=>$number_friend
        ]);
        
    }

    #[Route('/user/update_profil', name: 'app_update_profil')]

    public function updateProfil(Request $request): Response
    {
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);

        extract($data);

        $path = $this->getParameter('kernel.project_dir') . '/public/uploads/profil/';

        $user_serv = new UserService();

        if($image != "" ){

                $temp = explode(";", $image );

                $extension = explode("/", $temp[0])[1];

                $imagename = 'pdp_' . uniqid() . "." . $extension;

                file_put_contents($path . $imagename, file_get_contents($image));

                $user_serv->updateProfil($imagename, $user->getId());
        }

        return $this->json("Success");

    }

    #[Route('/user/update_couverture', name: 'app_update_couverture')]

    public function updateCouverture(Request $request): Response
    {
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);

        extract($data);

        $path = $this->getParameter('kernel.project_dir') . '/public/uploads/couverture/';

        $user_serv = new UserService();

        if($image != "" ){

                $temp = explode(";", $image );

                $extension = explode("/", $temp[0])[1];

                $imagename = 'pdc_' . uniqid() . "." . $extension;

                file_put_contents($path . $imagename, file_get_contents($image));

                $user_serv->updateCouverture($imagename, $user->getId());
        }

        return $this->json("Success");

    }
    #[Route('/user/all', name: 'app_all_user')]
    public function getUsers(): Response
    {
        $user = $this->getUser();

        $fullname = $user->getFirstname().' '.$user->getLastname();

        $user_table_friend = $user->getTablefriends();

        $user_tab_activity = $user->getTableactivity();

        $user_serv = new UserService();

        $user_list = $this->em->getRepository(User::class)->findAll();

        $friend_list = $user_serv ->getAllFriends($user_table_friend);

        $tab_user =array();

        //$isFriend = $user_serv->isFriend($user->getTablefriends(), $id);

        foreach($user_list as $users){
            array_push($tab_user, ["user"=>$users,"position"=>$user_serv->positionFriend($user_table_friend, $users->getId())]);
            //array_push($tab_user,$users);
        }

        //dd($tab_user);

        $activity_serv =  new VenteService();
        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

        //dd($tab_friend);

        return $this->render('user/all_user.html.twig', [
            'user'=>$user,
            'user_list'=>$tab_user,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number
        ]);

    }

    #[Route('/user/journal', name: 'app_all_journaux')]
    public function getJournaux(): Response
    {
        $user = $this->getUser();

        $fullname = $user->getFirstname().' '.$user->getLastname();

        $user_table_friend = $user->getTablefriends();

        $user_tab_activity = $user->getTableactivity();

        $user_serv = new UserService();

        $user_list = $this->em->getRepository(User::class)->findAll();

        $friend_list = $user_serv ->getAllFriends($user_table_friend);

        $tab_user =array();

        //$isFriend = $user_serv->isFriend($user->getTablefriends(), $id);

        foreach($user_list as $users){
            array_push($tab_user, ["user"=>$users,"position"=>$user_serv->positionFriend($user_table_friend, $users->getId())]);
            //array_push($tab_user,$users);
        }

        //dd($tab_user);
        $data_journal = array();

        $list_journal = $user_serv->getJournal("admin_journal");

        forEach($list_journal as $journal){
            array_push($data_journal, ["jour"=>$journal, "user"=>$this->em->getRepository(User::class)->findOneById($journal["user_id"])]);
        }

        $activity_serv =  new VenteService();
        $post_number =  $activity_serv->getPostNumber($user_tab_activity);
        $follower_number = $user_serv->getFollowerNumber($user_table_friend);
        $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

        //dd($tab_friend);

        return $this->render('user/journal.html.twig', [
            'user'=>$user,
            'user_list'=>$tab_user,
            'lastUsername' => $fullname,
            'post_number' => $post_number,
            'follower_number' => $follower_number,
            'suivi_number' => $suivi_number,
            'list_journal'=>$data_journal
        ]);

    }

}
