<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('businessname')
            ->add('industry')
            ->add('businessoverview')
            ->add('country')
            ->add('city')
            ->add('noOfinvitations')
            ->add('points')
            ->add('email')
            ->add('phone')
            ->add('badge')
            ->add('filetype')
            ->add('image')
            ->add('businessurl')
            ->add('logourl')
            ->add('referenceCode')
            ->add('file')
            ->add('logofile')
            ->add('password', 'repeated', [
                    'type' => 'password',
                        'first_options' => array('label' => 'password'),
                        'second_options' => array('label' => 'password_confirmation'),
                ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\User',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'backendbundle_user';
    }
}
