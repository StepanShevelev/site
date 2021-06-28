<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, array(
                    'attr'  =>  array('class' => 'form-control',
                        'style' => 'margin:15px 0;'),
                'label'=>'Кого зарегистрировать?',
                    'choices' =>
                        array
                        (
                            'Я Менеджер' => array
                            (
                                'ДА!' => 'ROLE_MANAGER',
                            ),
                            'Я Студент' => array
                            (
                                'ДА!' => 'ROLE_STUDENT'
                            ),

                        )
                ,
                    'multiple' => true,
                    'required' => true,
                )
            )
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
