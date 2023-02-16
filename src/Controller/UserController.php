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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        $nbr_nonLu = $notification ->getNotRead($table_notif);

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


        return $this->render('user/message.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user,
            'user_list' =>$user_list,
            'last_message' =>$last_msg,
            'all_message' => $all_message,
            'other_user' => $other_user,
        ]);
    }

    #[Route('/user/messages_by', name: 'app_messenger_user_id')]
    public function getMessageAll(): Response
    {
        $user = $this->getUser();

        $table_msg = $this->getUser()->getTablemessage();

        $msg_serv = new MessageService();

        //$all_message =$msg_serv->getMessageById($table_msg,$user_id);
        $all_message = $msg_serv->getAll($table_msg);

        $response = new StreamedResponse();
        
        //return $response;

        if(count($all_message)>0){
            $response->setCallback(function () use (&$all_message) {

                echo "data:" . json_encode($all_message) .  "\n\n";
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

        

        //extract($data); /// $from , $to , $message , $images
        
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
        return  $this->json("Publication succès");
        
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
        
        return  $this->json("Bien ajouter");
        
    }
    #[Route('/user/friends', name: 'app_friends')]
    public function getFriends(Request $request): Response
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


}
