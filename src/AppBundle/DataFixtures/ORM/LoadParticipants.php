<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Image;
use AppBundle\Entity\Participant;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class LoadParticipantsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker= Faker\Factory::create('be_BE');
        
        for ($i=0 ; $i< 10 ; $i++){
        $participant[0] = new Participant();
        $participant[0]->setNom($faker->name);
        $participant[0]->setPrenom($faker->firstName);
        
        $image[0]=new Image();
        $image[0]->setUrl("http://placekitten.com/10".$i."/10".$i);
        $image[0]->setAlt($faker->sentence(6));
        $participant[0]->setImage($image[0]);
        
        $manager->persist($participant[0]);
        $this->addReference('participant_'.$i, $participant[0]);
        }
        
        $manager->flush();
        
        
    }

    public function getOrder()
    {
        // indique l'ordre dans lequel les fixtures seront executees
        return 2;
    }
}
