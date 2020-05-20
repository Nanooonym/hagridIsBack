<?php

namespace App\Form;

use App\Entity\Sortie;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de la sortie :"
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => "Date et heure de la sortie :",
                'date_widget' => 'single_text'
            ])
            ->add('dateCloture', DateTimeType::class, [
                'label' => "Date limite d'inscription :",
                'date_widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => "Nombre de places :"
            ])
            ->add('duree', IntegerType::class, [
                'label' => "Durée :"
            ])
            ->add('descriptionInfos', TextareaType::class, [
                'label' => "Description et infos :"
            ])

            ->add('campus', CampusType::class, [
                'label' => false
            ])

            ->add('lieu', LieuType::class, [
                'label' => false
            ])

/*
            ->add(
                $builder->create('lieu', FormType::class, ['by_reference' => true])
                    ->add('nom', TextType::class)
                    ->add('rue', TextType::class)
                        ->add('latitude', TextType::class)
                        ->add('longitude', TextType::class)
                    ->add(
                        $builder->create('ville', FormType::class, ['by_reference' => true])
                            ->add('nom', TextType::class)
                            ->add('codePostal', TextType::class)
                    )
            )
*/



        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
