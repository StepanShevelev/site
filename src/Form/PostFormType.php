<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, array(
                'label' => 'Выбрать направление подготовки',
                'class' => Category::class,

                'choice_label'=> 'title',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ))
            ->add('title', TextType::class, array(
                'label' => 'Наименование Должности',
                'attr' => [
                'placeholder' => 'Введите текст'
                 ]
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'Описание Должности',
                'attr' => [
                    'placeholder' => 'Введите описание'
                ]
            ))
            ->add('user', EntityType::class, array(
                'label' => 'Введите email',
                'class' => User::class,
                'choice_label'=> 'email',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-success '
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
            'data_class' => Post::class,
        ]);
    }
}
