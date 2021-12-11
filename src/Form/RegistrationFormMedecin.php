<?php

namespace App\Form;

use App\Entity\Medecin;
use App\Entity\Specialite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationFormMedecin extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,[
                'attr' => [
                'class' =>'form-control',
                'placeholder' => 'Email',
                'type'=>'email',
                ]])
            ->add('nom',TextType::class,[
                'attr' => [
                'class' =>'form-control',
                'placeholder' => 'Nom',
                'type'=>'text',
                ]

            ])
            ->add('prenom',TextType::class,[
                'attr' => [
                'class' =>'form-control',
                'placeholder' => 'Prénom',
                'type'=>'text',
                ]

            ])
            ->add('adresse',TextType::class,[
                'attr' => [
                'class' =>'form-control',
                'placeholder' => 'Adresse',
                'type'=>'text',
                ]

            ])
            ->add('cp',TextType::class,[
                'attr' => [
                'class' =>'form-control',
                'placeholder' => 'Code postal',
                'type'=>'text',
                ]

            ])
            ->add('ville',TextType::class,[
                'attr' => [
                'class' =>'form-control',
                'placeholder' => 'Ville',
                'type'=>'text',
                ]

            ])
            ->add('telephone',TextType::class,[
                'attr' => [
                'class' =>'form-control',
                'placeholder' => 'Téléphone',
                'type'=>'text',
                ]

            ])
            ->add('sexe', ChoiceType::class,[
                'choices' =>[
                    'Homme' => 1,
                    'Femme' => 0
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('description', TextareaType::class,[
                'attr' =>[
                    'class' => 'form-control'
                ]
            ])
            ->add('carte_vitale', ChoiceType::class,[
                'choices' =>[
                    'Choisir' => null,
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('laSpecialite',EntityType::class,[
                'class' => Specialite::class,
                'choice_label' => "libelle",
                'attr' => [
                    'class' => 'form-control',
                ]        
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                    'type' => 'password',
                    'placeholder' => 'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medecin::class,
        ]);
    }
}
