<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, array(
                'label' => 'Заголовок категории, к которой относится акция',
                'class' => Category::class,
                'choice_label' => 'title', // какое поле из акций будет отображаться
                'disabled' => $options['is_category_stock']
            ))
            ->add('title', TextType::class, array(
                'label' => 'Название акции'
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Полное описание акции'
            ))
            ->add('image', FileType::class, array(
                'label' => 'Картинка',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/pjpeg',
                            'image/png',
                            'image/tiff'
                        ],
                        'mimeTypesMessage' => 'Доступны форматы JPEG, PNG, TIFF',
                    ])]
            ))
            ->add('date_start', DateType::class, array(
                'label' => 'Дата начала акции'
            ))
            ->add('date_end', DateType::class, array(
                'label' => 'Дата конца акции'
            ))
            ->add('rules', TextareaType::class, array(
                'label' => 'Правила проведения акции',
                'required' => false,
            ))
            ->add('thanks', TextareaType::class, array(
                'label' => 'Благодарности после окончания акции',
                'required' => false,
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
            'is_category_stock' => false
        ]);
    }
}
