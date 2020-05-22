<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de la sortie :",
            ])

            ->add('dateDebut', DateTimeType::class, [
                'label' => "Date et heure de la sortie :",
                'date_widget' => 'single_text',
                'format' => 'yyyy/MM/dd HH:mm',
            ])
            ->add('dateCloture', DateTimeType::class, [
                'label' => "Date limite d'inscription :",
                'date_widget' => 'single_text',
                'empty_data' => '',
                'format' => 'yyyy/MM/dd HH:mm',
            ])

            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => "Nombre de places :"
            ])
            ->add('duree', IntegerType::class, [
                'label' => "Durée :",
                'required' => false,
            ])
            ->add('descriptionInfos', TextareaType::class, [
                'label' => "Description et infos :",
                'required' => false,
            ])

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom'
            ])

            ->add('ville', EntityType::class, [
                'class' => 'App\Entity\Ville',
                'placeholder' => 'Selectionner une ville',
                'mapped' => false,
                'required' => false
            ]);
            $builder->get('ville')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event){
                    $ville = $event->getForm()->getData();
                    $form = $event->getForm();
                    $this->addLieuField($form->getParent(), $form->getData());
                }
            );
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event){
                    $data = $event->getData();
                    $lieu = $data->getLieu();
                    $form = $event->getForm();
                    if($lieu){
                        $ville = $lieu->getVille();
                        $this->addLieuField($form, $ville);
                        $form->get('ville')->setData($ville);
                        $form->get('lieu')->setData($lieu);
                    }else{
                        $this->addLieuField($form, null);
                    }
                }
            );
    }

    private function addLieuField (FormInterface $form, ?Ville $ville){
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'lieu',
            EntityType::class,
            null,
            [
                'class' => 'App\Entity\Lieu',
                'placeholder' =>  $ville ? 'Selectionner un lieu' : 'Selectionnez votre ville',
                //'mapped' => false,
                'required' => false,
                'choices' => $ville ? $ville->getLieux() : [],
                'auto_initialize' => false
            ]
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm();
            }
        );

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
