<?php

namespace Finortho\Fritage\EchangeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PackItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('pack', 'entity', array(
                'class' => 'Finortho\Fritage\EchangeBundle\Entity\Pack',
                'property' => 'name',
                'multiple' => false
            ))
            ->add('property', 'entity', array(
                'class' => 'Finortho\Fritage\EchangeBundle\Entity\PackProperty',
                'property' => 'name',
                'multiple' => true,
                'required' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Finortho\Fritage\EchangeBundle\Entity\PackItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'finortho_fritage_echangebundle_packitem';
    }
}