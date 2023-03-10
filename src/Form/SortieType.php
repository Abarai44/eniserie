<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,  [
                'label' => 'Nom de la sortie : '
            ])
            ->add('datedebut', DateTimeType::class,[
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie : '
            ])
            ->add('duree', NumberType::class,[
                'label'=>'Durée : '
            ])

            ->add('datecloture', DateType::class,[
                'html5' => true,
                'widget' => 'single_text',
                'label' => "Date limite d'inscription : "
            ])
            ->add('nbinscriptionsmax', NumberType::class,[
                'label'=>'Nombre de participants : '
            ])
            ->add('descriptioninfos',TextareaType::class,[
                'label'=>'Description : '
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom_lieu',
                'label' => 'Lieu : '

            ])
            ->add('Enregistrer',SubmitType::class,['label'=>'Enregistrer',
                'attr'=>[
                    'class'=>'button is-rounded'
                ]])

            ->add('Publier',SubmitType::class,['label'=>'Publier',
                'attr'=>[
                    'class'=>'button is-rounded'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
