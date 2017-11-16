<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'required' => true,
                'attr' => array('placeholder' => '+375 (XX) XXX-XX-XX', 'class' => 'phone', 'autofocus' => 'autofocus'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста заполните это поле',
                        'groups' => ['new_phone']
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Комментарий',
                'required' => true,
                'attr' => array('placeholder' => 'Ваш комментарий')
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form-horizontal form-label-left',
                'novalidate' => 'novalidate'
            ],
            'validation_groups' => function (FormInterface $form) {

                return ['new_phone'];
            }
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_cost';
    }

}