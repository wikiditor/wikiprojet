<?php

namespace App\Form;

//importe les classes et les interfaces nécessaires à la construction du formulaire
use App\Document\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

//définit une nouvelle classe de formulaire qui hérite d'AbstractType
class RegistrationFormType extends AbstractType
{
    /**
     * crée le formulaire d'inscription
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //ajoute le champ pour le username
            ->add('alias', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //ajoute le champ pour l'email' et un message d'erreur si le format est invalide
            ->add('email', EmailType::class,[  
                'label' => 'Adresse e-mail',
                'attr' => [
                    'class' => 'form-control'
                ],             
                'constraints' => [
                    new Email([
                        'message' => 'Veuillez entrer un format d\'email valide',
                    ]),
                ]
            ])
            //ajoute une case à cocher pour accepter les conditions
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les conditions',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez acceptez nos conditions d\'utilisation.',
                    ]),
                ],
            ])
            //permet à l'utilisateur d'entrer son mot de passe qui doit contenir au moins 6 caractères
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control'
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control'
                    ],
                ],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // longueur maximale permise par Symfony pour des raisons de sécurité
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    /**
     * spécifie que les données du formulaire seront utilisées pour créer un objet de la classe 'User'
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}