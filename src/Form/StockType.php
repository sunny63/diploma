<?php

namespace App\Form;

use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
                'label' => 'Правила проведения акции'
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
