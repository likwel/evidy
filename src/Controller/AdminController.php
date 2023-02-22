<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PDOService;
use App\Service\UserService;
use App\Service\CarteService;
use App\Service\VenteService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {

        $user = $this->getUser();

        if ($user->getRoles()[0] != "ROLE_GODMODE" ) {
            return $this->redirectToRoute('app_main');
        }else{

            $user_serv = new UserService();

            $carte_serv = new CarteService();
            
            $activity_serv =  new VenteService();

            $user_table_friend = $user->getTablefriends();

            $user_tab_activity = $user->getTableactivity();

            $fullname = $user->getFirstname().' '.$user->getLastname();


            $post_number =  $activity_serv->getPostNumber($user_tab_activity);
            $follower_number = $user_serv->getFollowerNumber($user_table_friend);
            $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

            return $this->render('admin/admin.html.twig', [
                'controller_name' => 'AdminController',
                'user'=>$user,
                'lastUsername' => $fullname,
                'post_number' => $post_number,
                'follower_number' => $follower_number,
                'suivi_number' => $suivi_number,
            ]);
        }
    }
    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(): Response
    {

        $user = $this->getUser();

        if ($user->getRoles()[0] != "ROLE_GODMODE" ) {
            return $this->redirectToRoute('app_main');
        }else{

            $user_serv = new UserService();

            $user_list = $this->em->getRepository(User::class)->findAll();

            $carte_serv = new CarteService();
            
            $activity_serv =  new VenteService();

            $user_table_friend = $user->getTablefriends();

            $user_tab_activity = $user->getTableactivity();

            $fullname = $user->getFirstname().' '.$user->getLastname();

            $tab_users = array();

            foreach($user_list as $users){
                array_push($tab_users,["amis"=>$user_serv->getAllFriendsId($users->getTablefriends()),"user"=>$users,"sale"=>$activity_serv->getAllSale($users->getTableactivity()),"notsale"=>$activity_serv->getAllNotSale($users->getTableactivity())]);
                //array_push($user_list,$users);
            }

            //dd($tab_users);

            $post_number =  $activity_serv->getPostNumber($user_tab_activity);
            $follower_number = $user_serv->getFollowerNumber($user_table_friend);
            $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

            return $this->render('admin/user_list.html.twig', [
                'controller_name' => 'AdminController',
                'user'=>$user,
                'user_list'=>$tab_users,
                'lastUsername' => $fullname,
                'post_number' => $post_number,
                'follower_number' => $follower_number,
                'suivi_number' => $suivi_number,
            ]);
        }
    }

    #[Route('/admin/sponsors', name: 'app_admin_sponsor')]
    public function sponsors(): Response
    {

        $user = $this->getUser();

        if ($user->getRoles()[0] != "ROLE_GODMODE" ) {
            return $this->redirectToRoute('app_main');
        }else{

            $user_serv = new UserService();

            $carte_serv = new CarteService();
            
            $activity_serv =  new VenteService();

            $user_table_friend = $user->getTablefriends();

            $user_tab_activity = $user->getTableactivity();

            $fullname = $user->getFirstname().' '.$user->getLastname();

            $tab_users = array();

            $all_sponsored = $user_serv->getAllSponsored("admin_sponsored");

            $tab_sponsors = array();

            foreach ($all_sponsored as $sponsor) {
                //$post_user = $this->em->getRepository(User::class)->findOneBy($sponsor["user_id"]);

                $table_user_post = "tb_activity_".$sponsor["user_id"];

                $all_post_activity = $activity_serv->getAll($table_user_post);

                foreach ($all_post_activity as $post_activity) {
                    if($post_activity["id"]==$sponsor["post_id"]){
                        array_push($tab_sponsors,$activity_serv->getOneBy($table_user_post, $sponsor["post_id"]));
                    }
                }

            }

            //dd($tab_sponsors);

            $post_number =  $activity_serv->getPostNumber($user_tab_activity);
            $follower_number = $user_serv->getFollowerNumber($user_table_friend);
            $suivi_number = $user_serv->getSuiviNumber($user_table_friend);

            return $this->render('admin/sponsors.html.twig', [
                'controller_name' => 'AdminController',
                'user'=>$user,
                'sponsors'=>$tab_sponsors,
                'lastUsername' => $fullname,
                'post_number' => $post_number,
                'follower_number' => $follower_number,
                'suivi_number' => $suivi_number,
            ]);
        }
    }
    #[Route('/admin/get_sum_user', name: 'app_admin_get_sum_user')]
    public function sommeUser(): Response
    {
        $user_serv = new UserService();
        return $this->json($user_serv->getSumUser());
    }

    #[Route('/admin/terminer_sponsor/{id}/{user_id}', name: 'app_terminer_sponsor')]
    public function terminer($id, $user_id): Response
    {
        $user_serv = new UserService();

        $user_serv->terminatedSponsor($id, $user_id);

        return $this->json("Success");
    }

    #[Route('/admin/add_sponsor/{id}/{user_id}', name: 'add_sponsor')]
    public function addSpons($user_id, $id): Response
    {
        $user_serv = new UserService();

        $user_serv->addSponsor($user_id, $id);
        
        return $this->json("Success");
    }
}
