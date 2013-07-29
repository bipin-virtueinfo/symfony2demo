<?php

namespace Admin\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Admin\CategoryBundle\Entity\CategoryRepository;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('parent', 'entity', array(
                'class' => 'Admin\CategoryBundle\Entity\Category',
                'query_builder' => function(CategoryRepository $er) {
                    return $er->createQueryBuilder('C')
                        ->orderBy('C.root, C.lft', 'ASC');
                    },
                'property' => 'indentedTitle',
                'empty_value' => 'Selct parent category',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\CategoryBundle\Entity\Category',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'admin_categorybundle_categorytype';
    }
}
