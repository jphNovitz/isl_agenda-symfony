<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom')
                ->add('description')
                ->add('debut', DateType::class)
                ->add('fin', DateType::class)
                ->add('participants', EntityType::class,['class'=>'AppBundle\Entity\Participant',
                    'multiple'=>true])
                ->add('categorie' ,EntityType::class,['class'=>'AppBundle\Entity\Categorie'])
                ->add('image', ImageType::class)
                ->add('ajout', SubmitType::class, ['label' => 'action !', 'attr' => array('class' => 'btn btn-default')])
                ->add('supprimer', SubmitType::class, ['label' => 'Supprimer !', 'attr' => array('class' => 'btn btn-danger')]);
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

}
