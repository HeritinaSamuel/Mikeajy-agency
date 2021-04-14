<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $content = '<p>'.join('</p><p>', $faker->paragraphs(5)).'</p>';

        for ($a=1; $a <= 20; $a++) { 
            $ad = new Ad();
            $title = $faker->sentence();
            $ad->setTitle($title)
                ->setPrice(mt_rand(200, 1000))
                ->setCoverImage("https://picsum.photos/1200/350?random=".mt_rand(1, 1000))
                ->setIntroduction($faker->paragraph(2))
                ->setContent($content)
                ->setRooms(mt_rand(2, 5));
            $manager->persist($ad);

            for ($i=1; $i < mt_rand(2, 5); $i++) { 
                $image = new Image();

                $image->setUrl("https://picsum.photos/900/450?random=".mt_rand(1, 20))
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
