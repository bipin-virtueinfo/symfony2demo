<?php

namespace Admin\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array('label' => 'First Name'))
            ->add('lastname', 'text', array('label' => 'Last Name'))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('avtar_image', 'hidden')
            ->add('upload_image', 'file', array('label' => 'Avtar Image', 'data_class' => null, "attr" => array("accept" => "image/*")));

        if(!$options['data']->getId()){
            $builder->add(
                  'password',
                  'repeated',
                  array(
                      'first_name' => 'Password',
                      'second_name' => 'Confirm_Password',
                      'type' => 'password',
                      'invalid_message' => "Password doesn't match",
                      'first_options'  => array('label' => 'Password'),
                      'second_options' => array('label' => 'Confirm Password'),
                  )
              );
          }
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\UserBundle\Entity\User',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'admin_userbundle_usertype';
    }
}
