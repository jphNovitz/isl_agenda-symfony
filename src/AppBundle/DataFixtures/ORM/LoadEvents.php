<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Image;
use AppBundle\Entity\Event;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class LoadEventsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker= Faker\Factory::create('be_BE');
        
        for ($i=0 ; $i< 10 ; $i++){
        $event[0] = new Event();
        $event[0]->setNom($faker->sentence(3));
        $event[0]->setDescription($faker->text(500));
        $event[0]->setDebut($faker->dateTimeThisYear);
        $event[0]->addParticipant($this->getReference('participant_'.$i));
        $event[0]->setCategorie($this->getReference('categorie_'.$i));
        
        $image[0]=new Image();
        $image[0]->setUrl("http://placekitten.com/20".$i."/20".$i);
        $image[0]->setAlt($faker->sentence(7));
        $event[0]->setImage($image[0]);
        
        $manager->persist($event[0]);
        $this->addReference('event_'.$i, $event[0]);
        }
        
        $manager->flush();
        
        
    }

    public function getOrder()
    {
        // indique l'ordre dans lequel les fixtures seront executees
        return 3;
    }
}
