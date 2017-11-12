<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateEditOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста заполните это поле',
                        'groups' => ['order']
                    ])
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'required' => true,
                'data' => ($options['data']->getPhone() != null) ? $options['data']->getPhone() : '+375 ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста заполните это поле',
                        'groups' => ['order'],
                    ])
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание',
                'required' => true,
            ])
            ->add(
                'status',
                ChoiceType::class,
                array(
                    'label' => 'Статус',
                    'choices' => array(
                        'В ожидании звонка' => 'Waiting for the call',
                        'Выполнен' => 'Completed',
                        'Недоступен' => 'Not available',
                    ),
                    'attr' => array(
                        'class' => 'selectpicker'
                    ),
                )
            )
            ->add(
                'time_to_call',
                ChoiceType::class,
                array(
                    'label' => 'Время звонка',
                    'choices' => array(
                        'В любое время' => 'Anytime',
                        'С 9 до 13' => 'Before noon',
                        'С 14 до 18' => 'After noon',
                    ),
                    'attr' => array(
                        'class' => 'selectpicker'
                    ),
                )
            )
            ->add('city', TextType::class, [
                'label' => 'Город',
                'required' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Order',
            'attr' => [
                'class' => 'form-horizontal form-label-left',
                'novalidate' => 'novalidate'
            ],
            'validation_groups' => function (FormInterface $form) {

                return ['order'];
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