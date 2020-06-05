<?php

namespace App\Form;

use App\Entity\Child;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stock', EntityType::class, array(
                'label' => 'Заголовок акции',
                'class' => Stock::class,
                'choice_label' => 'title' // какое поле из акций будет отображаться
            ))
            ->add('information', TextType::class, array(
                'label' => 'Информация о ребенке'
            ))
            ->add('serial_number', NumberType::class, array(
                'label' => 'Порядковый номер'
            ))
            ->add('institution_name', TextType::class, array(
                'label' => 'Название учреждения'
            ))
            ->add('group_name', TextType::class, array(
                'label' => 'Название группы (если есть)',
                'required' => false
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить'
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Child::class,
        ]);
    }
}
