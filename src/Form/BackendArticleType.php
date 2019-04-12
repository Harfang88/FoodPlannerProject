<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BackendArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                    'message' => 'Le titre ne peut pas être vide'
                ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                    'message' => 'Le contenu ne peut pas être vide'
                ])
                ]
            ])
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
