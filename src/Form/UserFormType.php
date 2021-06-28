<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class, array(
                'label'=>'Введите Email'
            ))
            ->add('org', TextType::class, array(
                'label' => 'Введите название организации',
                'attr' => [
                    'placeholder' => '(Только для менеджеров)'
                ]
            ))
            ->add('univ', TextType::class, array(
                'label' => 'Введите название университета',
                'attr' => [
                    'placeholder' => '(Только для студентов)'
                ]
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Введите номер телефона',
                'attr' => [
                    'placeholder' => '+7 123 123 12 12'
                ]
            ))

            ->add('fio', TextType::class, array(
                'label' => 'Введите имя и фамилию',
                'attr' => [
                    'placeholder' => 'Иванов Иван'
                ]
            ))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password']
            ])
            ->add('save', SubmitType::class, array(
                'label'=> 'Сохранить'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
