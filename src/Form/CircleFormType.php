<?php

namespace App\Form;

use App\Entity\Circle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CircleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => '"Quartier des tourterelles"  /  "Résidence Victor Hugo"',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Donnez un nom pour votre cercle',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le nom doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
            ]
            )
            ->add('address', TextType::class, [
                'label' => 'Adresse postale du cercle',
                'help' => 'Cela peut être le nom de la rue ou d\'une des rues du quartier. Laissez vide si le cercle concerne un village',
            ])
            ->add('postcode', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer le code postal du cercle',
                    ]),
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer la ville du cercle',
                    ]),
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'constraints' => [
                    new Country([
                        'message' => 'Sélectionnez un pays dans la liste',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez indiquer le pays du cercle',
                    ]),
                ],
                'preferred_choices' => ['FR'],
            ]
            )
            ->add('submit', SubmitType::class, [
                'label' => "Créer un cercle"
            ])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Circle::class,
        ]);
    }
}
