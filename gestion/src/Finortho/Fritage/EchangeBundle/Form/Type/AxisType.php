<?php

namespace Finortho\Fritage\EchangeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AxisType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('axis', 'choice', array(
                'choices'  => array(
                    'X' => 'X',
                    'Y' => 'Y',
                    'Z' => 'Z',
                    'OPERATEUR' => 'Je laisse à l\'apréciation de l\'opérateur'
                ),
                    'label' => 'L\'axe vertical de la pièce'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Finortho\Fritage\EchangeBundle\Entity\Stl'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'finortho_fritage_echangebundle_axis';
    }
}
