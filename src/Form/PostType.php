<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Title',
                    'class' => 'form-input'
                ]
            ])
            ->add('content', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Content',
                    'class' => 'form-input'
                ]
            ])
            ->add('post', SubmitType::class, [
                'attr' => [
                    'class' => 'remove-style-btn large-btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
