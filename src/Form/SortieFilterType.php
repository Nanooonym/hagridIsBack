<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\SortieFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'placeholder' => 'Tous les campus',
                'choice_label' => 'nom',
                'label' => 'Campus',
                'required' => false,
            ])

            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Le nom de la sortie contient :'
            ])
            ->add('dateDebut', DateTimeType::class, [

                'label' => 'Entre',
                'date_widget' => 'single_text',
                'required' => false,
            ])
            ->add('dateFin', DateTimeType::class, [
                'required' => false,
                'label' => 'et',
                'date_widget' => 'single_text'
            ])
            ->add('isOrganisateur', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties dont je suis l'organisateur/trice",
            ])
            ->add('isInscrit', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties auxquelles je suis inscrit/e",
            ])
            ->add('isNotInscrit', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties auxquelles je suis pas inscrit/e",
            ])
            ->add('passee', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties passées",
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortieFilter::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
}
