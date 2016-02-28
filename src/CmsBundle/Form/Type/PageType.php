<?php

namespace CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\Page',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('title', TextType::class, array('label' => 'Page Title'))
            ->add('url', TextType::class, array ('label' => 'Page URL'))
            ->add('content', TextareaType::class, array('label' => 'Page Content'))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and Add New'));
    }

    /*
     * The getName() method returns the identifier of this form "type".
     * Remember: They should be unique in all the third-party bundles installed 
     * in your application.
     */
    public function getName()
    {
        return 'page';
    }
} 

