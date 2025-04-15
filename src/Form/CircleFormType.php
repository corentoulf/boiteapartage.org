<?php

namespace App\Form;

use App\Config\CircleType;
use App\Entity\Circle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
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


use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;

use function PHPUnit\Framework\isNull;

class CircleFormType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                'attr' => [
                    'class' => 's2-select'
                ],
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de la boîte',
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
            ->add('address_finder', ChoiceType::class, [
                'label' => 'Adresse',
                'help' => 'Cela peut être le nom de la rue ou d\'une des rues du quartier. Indiquez au moins une ville.',
                'attr' => [
                    'class' => 's2-select city-finder'
                ],
                'placeholder' => 'Choose Item Type',
                'placeholder_attr' => ['disabled' => 'disabled'],
                'mapped' => false,
            ])
            ->add('address_result', HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'geocities-result'
                ],
            ])
            ->add('address', HiddenType::class)
            ->add('address_label', HiddenType::class)
            ->add('postcode', HiddenType::class)
            ->add('city', HiddenType::class)
            ->add('insee_code', HiddenType::class)
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('country', CountryType::class, [
                'preferred_choices' => ['FR'],
                'label_attr' => [
                    'class' => 'd-none'
                ],
                'attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Créer une boîte",
                'row_attr' => [
                    "class" => "d-grid gap-2"
                ]
            ])
        ;

        //add the selected option to the select menu address_finder just before submit
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event): void {
    
            // fetch submitted value
            $form = $event->getForm();
            $data = $event->getData()['address_finder'];
            //check not empty address_finder
            
            // retrieve original select field options, so we won't need to repeat them
            $opts = $form->get('address_finder')->getConfig()->getOptions();  

            // here we're adding our fetched submitted value to the list of select field options
            $opts['choices'][$data] = $data;

            // not sure if this is needed, but i like to leave it for clearity
            $form->remove('address_finder');

            // add reconfigured (=with changed options) field
            $form->add(child: 'address_finder', type: ChoiceType::class, options: $opts);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Circle::class,
        ]);
    }
}
