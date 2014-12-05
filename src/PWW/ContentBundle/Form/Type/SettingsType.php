<?php

namespace PWW\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFormat', 'text', array('label' => 'Date Format'))
            ->add('timeFormat', 'text', array('label' => 'Time Format'))
            ->add('updaterPath', 'text', array('label' => 'Updater Path', 'required' => false))
            ->add('pmsIpAddress', 'text', array('label' => 'PMS IP Address'))
            ->add('pmsWebPort', 'integer', array('label' => 'PMS Web Port'))
            ->add('pmsSecureWebPort', 'integer', array('label' => 'PMS Secure Web Port'))
            ->add('useHttps', 'checkbox', array('label' => 'Use HTTPS (optional)', 'required' => false))
            ->add('plexWatchDb', 'text', array('label' => 'plexWatch Database'))
            ->add('myPlexUsername', 'text', array('label' => 'Username (optional)', 'required' => false))
            ->add('myPlexPassword', 'password', array('label' => 'Password (optional)', 'required' => false))
            ->add('groupingGlobalHistory', 'checkbox', array('label' => 'Global History (optional)', 'required' => false))
            ->add('groupingUserHistory', 'checkbox', array('label' => 'User History (optional)', 'required' => false))
            ->add('groupingCharts', 'checkbox', array('label' => 'Charts (optional)', 'required' => false))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'settings';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PWW\ContentBundle\Entity\Settings',
        ));
    }
    
}