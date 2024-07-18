<?php

namespace App\Form;

use App\Config\CircleType;
use App\Entity\Circle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class CircleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $filesystem = new Filesystem();
        // $jsonCities = $filesystem->readFile('/some/path/to/file.txt');
        $builder
            ->add('circle_type', ChoiceType::class, [
                'label' => 'Cette boîte à partage est pour :',
                'choices' => [
                    'Une Résidence ou un immeuble' => 'building',
                    'Un quartier' => 'district',
                    'Un hameau ou un village' => 'village',
                    'Une entreprise' => 'company',
                    'Une association' => 'association',
                    'Autre' => 'other'
                ],
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'help' => 'Exemples : Résidence du Val Claret •  Quartier du Vaugrenier • Société AMG • ASI VOLLEY-BALL',
                'attr' => [
                    // 'placeholder' => 'Entrez un nom pour la boîte à partage',
                    'required' => true
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Donnez un nom pour votre boîte à partage',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le nom doit faire au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ]
            )
            ->add('address', TextType::class, [
                'label' => 'Adresse postale ',
                'required' => false,
                'help' => 'Cela peut être le nom de la rue ou d\'une des rues du quartier. Laissez vide si la boîte concerne tout un village.',
            ])
            ->add('postcode', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer le code postal',
                    ]),
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer la ville',
                    ]),
                ]
            ])
            ->add('json_city', ChoiceType::class, [
                'label' => 'json cicty',
                'mapped' => false
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'constraints' => [
                    new Country([
                        'message' => 'Sélectionnez un pays dans la liste',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez indiquer le pays',
                    ]),
                ],
                'preferred_choices' => ['FR'],
            ]
            )
            ->add('submit', SubmitType::class, [
                'label' => "Créer une boîte"
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
