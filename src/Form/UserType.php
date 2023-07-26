<?php

namespace App\Form;

use App\Document\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('roles', ChoiceType::class, [
            'choices'  => [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                // Ajoutez d'autres rôles si nécessaire
            ],
            'multiple' => true, // Un utilisateur peut avoir plusieurs rôles
            'expanded' => true, // Les rôles sont représentés par des boutons radio
        ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);        
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
