<?php

namespace App\Form\Back;

use App\Entity\Back\Severite;
use App\Entity\Back\Statut;
use App\Entity\Back\Tickets;
use App\Entity\Back\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre du ticket',
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du ticket',
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('statut', EntityType::class, [
                'class' => Statut::class,
                'choice_label' => 'titre',
                'attr' => [
                    'class' => 'form-select mb-3',
                ],
            ])
            ->add('severite', EntityType::class, [
                'class' => Severite::class,
                'choice_label' => 'titre',
                'attr' => [
                    'class' => 'form-select mb-3',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}
