<?php
namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

   /**
    * @Route("/bonjour/{prenom}/age/{age}", name="hello")
    * @Route("/salut", name="hello_base")
    * @Route("/bonjour/{prenom}", name="hello_prenom")
    * Montre la page qui dit bonjour
    * 
    * @return void
    */
   public function hello($prenom = "anonyme", $age = 0) {
     
      return $this->render(
         'hello.html.twig',
         [
            'prenom' => $prenom,
            'age' => $age
         ]

      );
   }

   /**
    * @Route("/", name="homepage")
    */
   public function home(AdRepository $adRepo, UserRepository $userRepo){
   
      return $this->render(
         'home.html.twig', [
            "ads" => $adRepo->findBestAds(3),
            "users" => $userRepo->findBestUsers()
         ]

      );
   
   }
}

?>