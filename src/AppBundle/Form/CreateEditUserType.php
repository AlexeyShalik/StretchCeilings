<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateEditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Имя',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста заполните это поле',
                        'groups' => ['new_user', 'edit_user']
                    ])
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста заполните это поле',
                        'groups' => ['new_user', 'edit_user'],
                    ]),
                    new Email([
                        'message' => 'Введите корректный адрес электронной почты',
                        'groups' => ['new_user', 'edit_user'],
                    ])
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'first_options' => [
                    'label' => 'Пароль',
                ],
                'second_options' => [
                    'label' => 'Повторите пароль',
                ],
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли не совпалают. Пожалуйста внимательно введите пароль еще раз.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста введите пароль',
                        'groups' => ['new_user']
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Пароль должен содержать минимум 4 символа',
                        'max' => 12,
                        'maxMessage' => 'Максимальная длина пароля 12 символов',
                        'groups' => ['new_user']
                    ])
                ],
            ])
            ->add('enabled', ChoiceType::class, [
                'label' => 'Статус пользователя',
                'required' => true,
                'choices' => [
                    'Активный' => true,
                    'Неактивный' => false
                ],
                'attr' => [
                    'class' => 'radio-button'
                ],
                'placeholder' => false,
                'multiple' => false,
                'expanded' => true,
            ]);
}

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'attr' => [
                'class' => 'form-horizontal form-label-left',
                'novalidate' => 'novalidate'
            ],
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['username'],
                    'message' => 'Указанное Имя уже используется в системе',
                    'groups' => ['new_user', 'edit_user']
                ]),
                new UniqueEntity([
                    'fields' => ['email'],
                    'message' => 'Указанный адрес уже используется в системе',
                    'groups' => ['new_user', 'edit_user']
                ])
            ],
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                if(!empty($data->getPlainPassword()) || is_null($data->getId())) {
                    return ['new_user'];
                }

                return ['edit_user'];
            }
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }

}