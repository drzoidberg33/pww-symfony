<?php

namespace PWW\DataFactoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('version')
            ->add('json')
            ->add('jsonPretty')
            ->add('hashRef')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PWW\DataFactoryBundle\Entity\Config'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pww_datafactorybundle_config';
    }
}
