<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Range;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => false,
                'attr'  => ['placeholder' => 'Nom'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nom '
                    ])
                ]
            ])
            ->add('quantity', null, [
                'attr' => ['min' => 0] ,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Quantité',
                    'class'       => 'w-25'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La quantité ne peut pas être vide'
                    ]),
                    new Range([
                        'min' => 0,
                        'minMessage' => 'La quantité ne peut pas être vide'
                    ])
                ]
            ])
            ->add('unit', ChoiceType::class, [
                'label'   => false,
                'attr'    => [
                    'placeholder' => 'Nom de l\'unité',
                    'style'       => 'width: 100px'
                ],
                'choices' => [
                    'pièces' => 'pièces',
                    'g' => 'g',
                    'l' => 'l'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'unité ne peut pas être vide'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
