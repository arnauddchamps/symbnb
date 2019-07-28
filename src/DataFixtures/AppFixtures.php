<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
         $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);


        $adminUser = new User();
        $adminUser->setFirstname('Arnaud')
            ->setLastname('Deschamps')
            ->setEmail('arnaud.dchamps@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'meduza'))
            ->setPicture('https://img.20mn.fr/HE93W5urTI62VYvZtKT1GQ/814x360_employee-restaurant-illustration')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
            ->addUserRole($adminRole);

        $manager->persist($adminUser);

        // Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <10; $i++){
            $user = new User;

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';

            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);
            
            $manager->persist($user);
            $users[] = $user;
        }

        // Nous gérons les annonces
        for($i = 1; $i <= 30; $i++){
            $ad = new Ad;

            $title = $faker->sentence(6);
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $description = '<p>' . join('</p><p>',$faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setDescription($description)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);


                for($j=1; $j<=(mt_rand(2, 5)); $j++){
                    $image = new Image();
                    $image->setUrl($faker->imageUrl(1000, 350))
                          ->setCaption($faker->sentence())
                          ->setAd($ad);
                    $manager->persist($image);
                }

                // Gestion des réservations
                for($j = 1; $j <= mt_rand(0, 10); $j++) {
                    $booking = new Booking();

                    $createdAt = $faker->dateTimeBetween('-6 months');
                    $startDate = $faker->dateTimeBetween('-3 monts');

                    $duration = mt_rand(3, 10);

                    $endDate = (clone $startDate)->modify("+$duration days");
                    $amount = $ad->getPrice() * $duration;
                    $booker = $users[mt_rand(0, count($users) -1)];

                    $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount);

                    $manager->persist($booking);
                    
                }
    
            $manager->persist($ad);
    
        }
       
        $manager->flush();
    }
}
