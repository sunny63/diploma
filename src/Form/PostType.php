<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, array(
                'label' => 'Заголовок категории',
                'class' => Category::class,
                'choice_label' => 'title' // какое поле из категорий будет отображаться
            ))
            ->add('title', TextType::class, array(
                'label' => 'Заголовок поста',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Описание поста',
                'attr' => [
                    'placeholder' => 'Введите описание'
                ]
            ))
            ->add('image', FileType::class, array(
                'label' => 'Главное изображение',
                'required' => false,
                'mapped' => false
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
