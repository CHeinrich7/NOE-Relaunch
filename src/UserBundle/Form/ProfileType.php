<?php
namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProfileType
 * @package UserBundle\Form
 */
class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $inputAttr = array(
            'class-label'   => 'col-md-offset-2 col-md-3 col-sm-offset-1 col-sm-4',
            'class'         => 'col-md-3 col-sm-4'
        );

        $buttonAttr = array(
            'class'         => 'col-sm-9 col-md-8'
        );

        $builder
            ->add('email',      'text',     array('label' => 'E-Mail', 'attr' => $inputAttr))
            ->add('number',     'text',     array('label' => 'Telefon', 'attr' => $inputAttr))
            ->add('number2',    'text',     array('label' => 'Telefon (optional)', 'attr' => $inputAttr))
            ->add('firstname',  'text',     array('label' => 'Vorname', 'attr' => $inputAttr))
            ->add('lastname',   'text',     array('label' => 'Nachname', 'attr' => $inputAttr))
            ->add('showMail',   'checkbox', array('label' => 'E-Mail für alle anzeigen', 'attr' => $inputAttr))
            ->add('image',      'text',     array('label' => 'Userbild', 'attr' => $inputAttr))
            ->add('save',       'submit',   array('label' => 'Speichern', 'attr' => $buttonAttr))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return __CLASS__;
    }
} 