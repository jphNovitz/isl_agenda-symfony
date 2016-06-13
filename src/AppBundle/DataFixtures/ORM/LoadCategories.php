<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Categorie;

use Faker;

class LoadCategoriesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker= Faker\Factory::create('be_BE');
        
        for ($i=0 ; $i< 10 ; $i++){
        $categorie[0] = new Categorie();
        $categorie[0]->setNom($faker->sentence(2));
        
        $manager->persist($categorie[0]);
        $this->addReference('categorie_'.$i, $categorie[0]);
        }
        
        $manager->flush();
        
        
    }

    public function getOrder()
    {
        // indique l'ordre dans lequel les fixtures seront executees
        return 1;
    }
}
