<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * 
     */
    public function index(AdRepository $repo, $page)
    {
        $limit = 10;

        $start = $page * $limit - $limit;
        
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findBy([], [], $limit, $start)
        ]);
    }

    /**
     * Permet d'afficher le form d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce a bien été enregistrée"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            "ad" => $ad,
            "form" => $form->createView()
        ]);
    }
    
    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     */
    public function delete(Ad $ad, ObjectManager $manager){

        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                "warning",
                "Vous ne pouvez pas supprimre l'annonce car elle possede déjà des réservations"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'annonce a bien été supprimée"
            );
        }


        return $this->redirectToRoute('admin_ads_index');
    }
}
