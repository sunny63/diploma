<?php

namespace App\Form;

use App\Entity\Photo;
use App\Entity\PhotoReport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, array(
                'label' => 'Загрузить фото',
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
            ->add('photoReport', EntityType::class, array(
                'label' => 'Заголовок фотоотчета, к которой относится фотография',
                'class' => PhotoReport::class,
                'choice_label' => 'title'
            ))
            ->add('description', TextType::class, array(
                'label' => 'Описание фото'
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
