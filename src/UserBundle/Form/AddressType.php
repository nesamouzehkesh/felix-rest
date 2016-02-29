<?php

namespace UserBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use UserBundle\Entity\User;
use UserBundle\Entity\Address;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

/**
 * 
 */
class AddressType extends AbstractType
{
    private $user;
    
    /**
     * @param User $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->user instanceof User) {
            $builder
                ->add('fullName', TextType::class, array('required' => false));
        } else {
            $builder
                ->add('fullName', TextType::class, array('data' => $this->user->getName(), 'required' => false));
        }
        
        $builder
            ->add('locationType', ChoiceType::class, array(
                'choices' => Address::$locationTypes, 
                'required' => false
                ))
            ->add('firstAddressLine', TextType::class, array('required' => false))
            ->add('secondAddressLine', TextType::class, array('required' => false))
            ->add('city', TextType::class, array('required' => false))
            ->add('state', TextType::class, array('required' => false))
            ->add('postCode', TextType::class, array('required' => false))
            ->add('country', CountryType::class, array('required' => false))
            ->add('phoneNumber', TextType::class, array('required' => false));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Address',
        ));
    }
    
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'user_address_form';
    }
}