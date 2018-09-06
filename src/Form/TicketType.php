<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prenom'
            ])
            ->add('birth', DateType::class, [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'attr' => [
                    'class' => 'birth'
                ]
            ])
            ->add('country', CountryType::class, [
                'choice_translation_locale' => 'fr',
                'preferred_choices' => array('FR')
            ])
            ->add('reduced', CheckboxType::class, [
                'required' => false,
                'label' => 'tarif réduit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}