<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class LoginType
 * @package UserBundle\Form
 */
class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $inputAttr = array(
            //'class-label'   => 'col-md-offset-2 col-md-3 col-sm-offset-1 col-sm-4',
            'class'         => 'col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4',
            'autocomplete'  => 'off'
        );

        $buttonAttr = array(
            'class'         => 'col-sm-9 col-md-8'
        );

        $builder
            ->add('_username', Type\TextType::class, array('label' => false, 'attr' => array_merge($inputAttr, ['placeholder' => 'Username', 'icon-widget' => 'user'])))
            ->add('_password', Type\PasswordType::class, array('label' => false, 'attr' => array_merge($inputAttr, ['placeholder' => 'Passwort', 'icon-widget' => 'lock'])))
            ->add('save', Type\SubmitType::class, array('label' => 'Einloggen', 'attr' => $buttonAttr))
        ;
    }
} 