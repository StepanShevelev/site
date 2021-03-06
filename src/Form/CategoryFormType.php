<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(

                'label' => 'Направление подготовки',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ))
            ->add('description',TextareaType::class, array(

                'label' => 'Описание',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ))
            ->add('save',SubmitType::class, array(
                'label'=>'Сохранить',
                'attr'=>[
                    'class'=>'btn btn-success float-left mr-3'
                ]
            ))
            ->add('delete',SubmitType::class, array(
                'label' => 'Удалить',
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
