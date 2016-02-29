<?php

namespace UserBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * 
 */
class UserType extends AbstractType
{
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User',
        ));
    }
        
    /**
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class);
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $user = $event->getData();

            $form
                ->add('password', PasswordType::class, array(
                    'mapped' => false, 
                    'required' => false
                    ))
                ->add('rePassword', PasswordType::class, array(
                    'mapped' => false, 
                    'required' => false
                ));

            if (null !== $user->getId()) {
                if ($user->hasAdminRole()) {
                    $form->add('currentPassword', PasswordType::class, array(
                        'mapped' => false, 
                        'required' => false
                        ));
                }

                $form->add('changePassword', CheckboxType::class, array(
                    'mapped' => false,
                    'data' => false,
                    'label'    => 'I want to change the password',
                    'required' => false,
                    ));                    
            }
        });        
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'user_form';
    }
}