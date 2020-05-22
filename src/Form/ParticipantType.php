<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',TextType::class, [
                'label' => "Pseudo:"
            ])
            ->add('prenom', TextType::class, [
                'label'=> "Prénom:"
            ])
            ->add('nom', TextType::class, [
                'label'=> "Nom:"
            ])
            ->add('telephone', TextType::class, [
                'label' => "Téléphone:"
            ])
            ->add('mail', TextType::class, [
                'label'=> "Email:"
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Entrée votre mot de passe.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' =>true,
                'first_options' => ['label'=> "Mot de passe:"],
                'second_options' => ['label' => "Confirmation"],
            ])

            ->add('campus', EntityType::class, [
                'label' => false,
               'choice_label' => "nom",
                'class'=> Campus::class

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
