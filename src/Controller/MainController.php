<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Service\VenteService;
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
        $user_serv = new UserService();

        if($user_serv->isInstalled("localhost", "evidy", "root", "") == false){
            return new RedirectResponse($this->urlGenerator->generate('app_install'));
        }else{

        $user = $this->getUser();

        //$lastUsername = $authenticationUtils->getLastUsername();
        //$lastUsername = $user->getEmail();

        $user_list = $this->em->getRepository(User::class)->findByUser();

        $activity_serv = new VenteService();

        if($user == null){
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }else{

            $fullname = $user->getFirstname().' '.$user->getLastname();

            $user_tab_activity = $user->getTableactivity();

            $user_tab_friend = $user->getTablefriends();

            $all_activity = $activity_serv->getAll($user_tab_activity);

            $all_friends = $user_serv->getAllFriends($user_tab_friend);

            $tab_not_friend = array();

            $tab_friend = array();

            $spons = "admin_sponsored";

            $all_sponsored = $user_serv->getAllSponsored($spons);

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

            foreach($user_list as $users){
                array_push($tab_not_friend, ["user"=>$users,"position"=>$user_serv->positionFriend($user_tab_friend, $users->getId())]);
            }

            forEach($all_friends as $friend){
                array_push($tab_friend, $this->em->getRepository(User::class)->findOneById($friend["user_id"]));
                
            }

            $list_user_id = array();

            forEach($all_friends as $friend){
                //$user = $this->em->getRepository(User::class)->findOneById($friend["user_id"]);
                array_push($list_user_id, $friend["user_id"]);
                
            }
            
            //dd($activity_serv->getAllByListUser($user->getId(), $list_user_id));
            $all_activity = $activity_serv->getAllByListUser($user->getId(), $list_user_id);

            $data = array();

            //dd($all_activity);
            forEach($all_activity as $activity){
                array_push($data, ["user"=>$this->em->getRepository(User::class)->findOneById($activity["user_id"]),"activity"=>$activity]);
            }
            //dd($data);
            $data_journal = array();

            $list_journal = $user_serv->getJournal("admin_journal");
            
            if(count($list_journal) > 0){
                forEach($list_journal as $journal){
                    array_push($data_journal, ["jour"=>$journal, "user"=>$this->em->getRepository(User::class)->findOneById($journal["user_id"])]);
                }
            }
            //dd($data_journal);

            $post_number =  $activity_serv->getPostNumber($user_tab_activity);
            $follower_number = $user_serv->getFollowerNumber($user_tab_friend);
            $suivi_number = $user_serv->getSuiviNumber($user_tab_friend);

            $list_famille =  $activity_serv->getListeFamille($user_tab_activity);
            $list_category =  $activity_serv->getListeCategory($user_tab_activity);

            $user_wait = $user_serv->getNbFriendWait($user_tab_friend);

            //dd($data);

            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'lastUsername' => $fullname,
                'user' => $user,
                'user_list' => $user_list,
                "activities" => $data,
                'friends' => $tab_friend,
                'suggestions' => $tab_not_friend,
                'sponsors' => $tab_sponsors,
                'post_number' => $post_number,
                'follower_number' => $follower_number,
                'suivi_number' => $suivi_number,
                'list_journal'=>$data_journal,
                'list_famille'=>$list_famille,
                'list_category'=>$list_category,
                'user_wait' => $user_wait
            ]);
        }
        
    }
}
}
