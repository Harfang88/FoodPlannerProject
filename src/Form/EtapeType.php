<?php

namespace App\Form;

use App\Entity\Etape;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotBlank;

class EtapeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etapeOrder', null, [
                'label' => false,
                'attr'  => [
                    'class' => 'w-25',
                    'placeholder' => 'N° étape',
                    'min' => 0,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le numéro d\'étape ne peut pas être vide'
                    ]),
                    new Range([
                        'min' => 0,
                        'minMessage' => 'Le numero d\'étape ne peut pas être vide'
                    ])
                ]
            ])
            ->add('description', null, [
                'label' =>false,
                'attr'  => [ 'placeholder' => 'Décrivez l\'étapes' ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etape::class,
        ]);
    }
}
