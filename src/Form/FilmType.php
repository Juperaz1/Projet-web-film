<?php

namespace App\Form;
use App\Entity\Film;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('titre', TextType::class, [
                'label' => 'Titre du film',
                'attr' => ['class' => 'form-control','placeholder' => 'Ex: Inception']
            ])
            ->add('annee', IntegerType::class, [
                'label' => 'Année de sortie',
                'attr' => ['class' => 'form-control','min' => 1900,'max' => date('Y') + 5,'placeholder' => 'Ex: 2023']
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes)',
                'attr' => ['class' => 'form-control','min' => 1,'placeholder' => 'Ex: 120']
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Synopsis / Résumé',
                'required' => false,
                'attr' => ['class' => 'form-control','rows' => 4,'placeholder' => 'Décrivez l\'histoire du film...']
            ])
            ->add('prixLocationDefault', NumberType::class, [
                'label' => 'Prix de location (€)',
                'attr' => ['class' => 'form-control','step' => '0.01','min' => 0,'placeholder' => 'Ex: 4.99']
            ])
            ->add('cheminAffiche', UrlType::class, [
                'label' => 'URL de l\'affiche',
                'required' => false,
                'attr' => ['class' => 'form-control','placeholder' => 'https://exemple.com/affiche.jpg']
            ])
            ->add('note', NumberType::class, [
                'label' => 'Note (sur 5)',
                'required' => false,
                'attr' => ['class' => 'form-control','min' => 0,'max' => 5,'step' => '0.1','placeholder' => 'Ex: 4.5'
                ]
            ])
            ->add('genres', EntityType::class, [
                'label' => 'Genres du film',
                'class' => Genre::class,
                'choice_label' => 'libelleGenre',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'form-select','size' => 4]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Film::class,]);
    }
}