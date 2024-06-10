<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\ItemCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => '"Perçeuse sans fil"  /  "Moule à madeleines"',
                    'required' => true
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Donnez un nom pour votre objet',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le nom doit faire au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ]
            )
            ->add('category', EntityType::class, [
                'class' => ItemCategory::class,
                'label' => "Catégorie",
                'attr' => [
                    'required' => true
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['update_mode'] === true ? "Mettre à jour" : "Ajouter"
            ])
            ->add('submitAndAdd', SubmitType::class, [
                'label' => "Ajouter au placard et ajouter un autre",
                'attr' => [
                    'class' => 'btn-outline-primary',
                    'hidden' => $options['update_mode']
                ]
            ])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'update_mode' => false
        ]);
    }
}
