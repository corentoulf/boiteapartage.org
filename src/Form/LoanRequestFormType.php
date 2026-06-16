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
            ->add('requested_start_date', DateType::class, [
                'label' => 'Date d\'emprunt souhaitée',
                'required' => true
            ])
            ->add('requested_end_date', DateType::class, [
                'label' => 'Date de retour souhaitée',
                'required' => true
            ])
            ->add('request_message', TextareaType::class, [
                'label' => 'Ajouter un message à l’attention du propriétaire',
                'help' => 'Expliquez votre besoin, cela favorise les échanges :)',
                'sanitize_html' => true,
                'attr' => [
                    'placeholder' => 'Bonjour, cet ouvrage m’intéresse particulièrement...'
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
