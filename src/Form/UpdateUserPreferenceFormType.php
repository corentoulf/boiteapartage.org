<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateUserPreferenceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('acceptEmailContact', CheckboxType::class, [
                'label'    => 'J\'accepte d\'être contacté par email',
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
            ->add('acceptPhoneContact', CheckboxType::class, [
                'label'    => 'J\'accepte d\'être contacté par téléphone',
                'required' => false,
                'disabled' => !$options['userHasPhone'],
                'help' => $options['userHasPhone'] ? '' : 'Ajoutez votre numéro à vos informations pour pouvoir activer le contact par téléphone',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'userHasPhone' => false
        ]);
    }
}
