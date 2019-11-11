<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll(),
        ]);
    }

    /**
     * Permet de modifier une réservation
     * 
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdminBookingType::class, $booking);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation a bien été modifiée"
            );
        }

        return $this->render('admin/booking/edit.html.twig',[
            "booking" => $booking,
            "form" => $form->createView()
        ]);
    }
    
    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     * 
     */
    public function delete(Booking $booking, ObjectManager $manager){

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );
        
        return $this->redirectToRoute('admin_booking_index');
    }
}
