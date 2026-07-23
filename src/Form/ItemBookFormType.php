<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\ItemType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ItemBookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('itemType', EntityType::class, [
                'class' => ItemType::class,
                'query_builder' => function (EntityRepository $er) use($options): QueryBuilder {
                    return $er->createQueryBuilder('it')
                        ->andWhere('it.category = :val')
                        ->setParameter('val', $options['itemCategory'])
                        ->orderBy('it.label', 'ASC');
                },
                'data' => $options['preferedItemType'],
                'label' => "Type d'ouvrage",
                'choice_label' => 'label',
                'required' => true,
                'attr' => [
                    'hidden' => false
                ],
                'label_attr' => [
                    'hidden' => false
                ] 
            ])
            ->add('property_1', TextType::class, [
                'label' => "Titre",
                'required' => true,
                'attr' => [
                    'readonly' => !$options['canEditInfo']
                ]
            ])
            ->add('property_2', TextType::class, [
                'label' => "Auteur(s)",
                'required' => true,
                'attr' => [
                    'readonly' => !$options['canEditInfo']
                ]
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
            ->add('imageFile', FileType::class, [
                'label' => "Photo de la 1ère de couverture",
                'required' => false,
                'attr' => [
                    'hidden' => true,
                ],
                'label_attr' => [
                    'hidden' => false,
                    'class' => 'required'
                ],
                'row_attr' => [
                    'class' => 'mb-0'
                ],
                'constraints' => [
                    new Assert\File(
                        maxSize: '5M',
                        maxSizeMessage: 'Merci de mettre un fichier moins volumineux (<5MB)',
                        extensions: ['jpg', 'jpeg','png','tiff']
                    )
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
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            $imageFile = $form->get('imageFile')->getData();
            $link = $form->get('property_3')->getData();

            if (empty($imageFile) && empty($link)) {
                $form->addError(new \Symfony\Component\Form\FormError(
                    "Merci d'ajouter une photo du livre."
                ));
            }
        });
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'update_mode' => false,
            'itemCategory' => null,
            'preferedItemType' => null,
            'canEditInfo' => true
        ]);
    }
}