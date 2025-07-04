<?php

namespace App\Form\Front;

use App\Entity\Back\Contribution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributionFrontType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la bonne pratique',
                'attr' => [
                    'placeholder' => 'Ex: Sauvegarde régulière des données',
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('corpshtml', TextareaType::class, [
                'label' => 'Corps de la bonne pratique',
                'attr' => [
                    'placeholder' => 'Décrivez la bonne pratique...',
                    'class' => 'form-control mb-3',
                    'rows' => 6
                ],
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contribution::class,
        ]);
    }
}
