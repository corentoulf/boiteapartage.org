<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\ItemType;
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

class ItemBookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('itemType', EntityType::class, [
                'class' => ItemType::class,
                'label' => "Catégorie",
                'required' => true,
                'attr' => [
                    'hidden' => true
                ],
                'label_attr' => [
                    'hidden' => true
                ] 
            ])
            ->add('property_1', TextType::class, [
                'label' => "Titre",
                'required' => true
            ])
            ->add('property_2', TextType::class, [
                'label' => "Auteur(s)",
                'required' => true
            ])
            ->add('property_3', TextType::class, [
                'label' => "image_thumbnail_link",
                'required' => false,
                'attr' => [
                    'hidden' => true
                ],
                'label_attr' => [
                    'hidden' => true
                ] 
            ])
            ->add('property_4', TextType::class, [
                'label' => "api_reference_link",
                'required' => false,
                'attr' => [
                    'hidden' => true
                ],
                'label_attr' => [
                    'hidden' => true
                ] 
            ])
            ->add('property_5', TextType::class, [
                'label' => "",
                'required' => false,
                'attr' => [
                    'hidden' => true
                ],
                'label_attr' => [
                    'hidden' => true
                ] 
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['update_mode'] === true ? "Mettre à jour" : "Ajouter"
            ])
            ->add('submitAndAdd', SubmitType::class, [
                'label' => "Ajouter et continuer",
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