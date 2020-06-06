<?php

namespace App\Form;

use App\Entity\PhotoReport;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PhotoReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stock', EntityType::class, array(
                'label' => 'Заголовок акции, к которой относится фотоотчет',
                'class' => Stock::class,
                'choice_label' => 'title' // какое поле из акций будет отображаться
            ))
            ->add('title', TextType::class, array(
                'label' => 'Заголовок'
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Описание фотоотчета'
            ))
            ->add('image', FileType::class, array(
                'label' => 'Главная фотография',
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
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhotoReport::class,
        ]);
    }
}
