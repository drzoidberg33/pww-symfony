<?php

namespace PWW\DataFactoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupedType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sessionId')
            ->add('user')
            ->add('platform')
            ->add('title')
            ->add('origTitle')
            ->add('origTitleEp')
            ->add('episode')
            ->add('season')
            ->add('year')
            ->add('rating')
            ->add('genre')
            ->add('summary')
            ->add('notified')
            ->add('pausedCounter')
            ->add('xml')
            ->add('ipAddress')
            ->add('ratingkey')
            ->add('parentratingkey')
            ->add('grandparentratingkey')
            ->add('time')
            ->add('stopped')
            ->add('paused')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PWW\DataFactoryBundle\Entity\Grouped'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pww_datafactorybundle_grouped';
    }
}
