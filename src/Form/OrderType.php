<?php

namespace App\Form;


use App\Entity\Order;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('choiceDate', DateType::class,[
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label' => 'Date de visite'
            ])
            ->add('half', CheckboxType::class, [
                'required' => false,
                'label' => 'demi-journée'
            ])
            ->add('tickets', CollectionType::class, [
                "entry_type"    => TicketType::class,
                "allow_add"     => true,
                "allow_delete"  => true,
                "by_reference"  => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}