<?php

namespace AppBundle\Form;

use AppBundle\Entity\Image;
use AppBundle\Entity\Theme;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('background', EntityType::class, [
                'class'         => Image::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.title');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Theme::class,
        ]);
    }

    public function getName()
    {
        return 'app_bundle_theme_type';
    }
}
