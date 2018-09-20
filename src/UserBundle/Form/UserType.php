<?php

namespace UserBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use ToolboxBundle\Services\EntitySecurityService;
use UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class UserType
 * @package UserBundle\Form
 */
class UserType extends AbstractType
{
    /**
     * @var EntitySecurityService
     */
    private $entitySecurityService;

    /**
     * @param EntitySecurityService $entitySecurityService
     */
    public function __construct(EntitySecurityService $entitySecurityService)
    {
        $this->entitySecurityService = $entitySecurityService;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $builder->getData();

        $lowerRoles = $this->entitySecurityService->getLowerRoles();

        $inputAttr = array(
            'class-label'   => 'col-md-offset-2 col-md-3 col-sm-offset-1 col-sm-4',
            'class'         => 'col-md-3 col-sm-4',
            'class-widget'  => 'no-chosen',
            'minlength'     => 5
        );

        $buttonAttr = array(
            'class' => 'col-sm-9 col-md-8'
        );

        $builder
            ->add('username', Type\TextType::class, array('label' => 'Username', 'attr' => $inputAttr));

        if(count($lowerRoles)) {
            $builder
                ->add('role', EntityType::class,   array(
                    'label'     => 'Rolle',
                    'attr'      => $inputAttr,
                    'class'     => 'UserBundle\Entity\Role',
                    'query_builder' => function(EntityRepository $repository) use ($lowerRoles) {
                        $qb = $repository->createQueryBuilder('r');

                        return $qb
                            ->where('r.role IN (:roles)')
                            ->setParameter('roles', $lowerRoles);
                    },
                ));
        }

        if($this->entitySecurityService->isEntityGranted($user)) {
            $builder
                ->add('old_password', Type\PasswordType::class, array(
                    'label' => 'altes Passwort',
                    'attr'  => $inputAttr,
                    'constraints'   => array(
                        new Constraints\Length([
                            'min'           => 5,
                            'minMessage'    => 'Mindestlänge: 5 Zeichen']),
                        new Constraints\NotBlank([
                            'message'       => 'Dieses Feld darf nicht leer sein'
                        ]),
                        new SecurityAssert\UserPassword([
                            'message'       => 'Das angegebene Passwort ist falsch'
                        ])
                    )
                ));
        }

        $builder
            ->add('new_password',  Type\RepeatedType::class, array(
                'type'              => Type\PasswordType::class,
                'invalid_message'   => 'Die Passwortfelder müssen übereinstimmen',
                'required'          => true,
                'first_options'     => array(
                    'label' => 'neues Passwort',
                    'attr' => $inputAttr,
                    'constraints'   => array(
                        new Constraints\Length([
                            'min'           => 5,
                            'minMessage'    => 'Mindestlänge: 5 Zeichen']),
                        new Constraints\NotBlank([
                            'message'       => 'Dieses Feld darf nicht leer sein'
                        ])
                    )
                ),
                'second_options'    => array(
                    'label' => 'Passwort wiederholen',
                    'attr' => $inputAttr,
                    'constraints'   => array(
                        new Constraints\Length([
                            'min'           => 5,
                            'minMessage'    => 'Mindestlänge: 5 Zeichen']),
                        new Constraints\NotBlank([
                            'message'       => 'Dieses Feld darf nicht leer sein'
                        ])
                    )
                )
            ))
            ->add('save', Type\SubmitType::class, array('label' => 'Speichern', 'attr' => $buttonAttr))
        ;
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults(array(
            'data_class'   => 'UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'usertype';
    }
} 