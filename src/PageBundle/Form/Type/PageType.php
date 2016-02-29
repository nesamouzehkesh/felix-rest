<?php

namespace PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PageBundle\Entity\Page',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('title', TextType::class, array('label' => 'Page Title'))
            ->add('url', TextType::class, array ('label' => 'Page URL'))
            ->add('content', TextareaType::class, array('label' => 'Page Content'));
    }

    /*
     * The getName() method returns the identifier of this form "type".
     * Remember: They should be unique in all the third-party bundles installed 
     * in your application.
     */
    public function getName()
    {
        return 'felix_page';
    }
} 

