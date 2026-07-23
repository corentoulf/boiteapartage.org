<?php

namespace App\Form;

use App\Entity\Loan;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoanRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('request_message', TextareaType::class, [
                'required' => true,
                'label' => 'Formulez votre demande',
                'help' => 'Expliquez votre besoin, la période souhaitée, etc..',
                'sanitize_html' => true,
                'attr' => [
                    'rows'=> 4,
                    'placeholder' => 'Bonjour, je dois monter un meuble ce weekend...'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer la demande",
                'row_attr' => [
                    'class' => 'd-grid gap-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
