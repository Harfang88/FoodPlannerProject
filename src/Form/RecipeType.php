<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Form\EtapeType;
use App\Form\RecipeIngredientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Ingredient;


class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                'label'       => 'Titre',
                'attr'        => ['placeholder' => 'Titre de la recette'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre ne peut pas être vide'
                    ])
                ]
            ])
            ->add('calorie', null,[
                'label'       => 'Nombre de calories',
                'attr'        => [
                    'placeholder' => '550 Kcal',
                    'style' => 'max-width:150px'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nombre de calories ne peut pas être vide'
                    ])
                ]
            ])
            ->add('difficulty', ChoiceType::class,[
                'label'      => 'Difficulté',
                'choices'    => [
                    'Facile'      => 1,
                    'Normale'     => 2,
                    'Expérimenté' => 3,
                    'Difficile'   => 4,
                    'MOF'         => 5,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La difficulté ne peut pas être vide'
                    ])
                ]
            ])
            ->add('time', TimeType::class,[
                'widget'      => 'choice',
                'label'       => 'Durée de préparation',
                'html5' => false,
                'input'       => 'datetime',
                'placeholder' => [
                    'hour'   => 'Heures',
                    'minute' => 'Minutes',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le durée ne peut pas être vide'
                    ])
                ]
                
            ])
            ->add('picture', null, [
                'label'      => 'Photo de présentation',
                'empty_data' => 'https://images.unsplash.com/photo-1458644267420-66bc8a5f21e4?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1493&q=80',
                'attr'       => [
                    'placeholder' => 'https://url_de_la_photo.fr'
                ],
            ])
            ->add('type', null, [
                'label' => 'Type de plat',
            ])
            
            ->add('etapes', CollectionType::class, [
                'entry_type'    => EtapeType::class,
                'entry_options' => ['label' => false],
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => 'false',
                'label'         => false
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type'    => IngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => 'false',
                'label'         => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
