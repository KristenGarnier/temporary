<?php

namespace Finortho\Fritage\EchangeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExigenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite', 'number', array('label' => 'Quantité désirée'))
            ->add('fonctionnel', 'checkbox', array('label' => 'Doit t\'il être fonctionnel ?', 'required' => false))
            ->add('clinique', 'checkbox', array('label' => 'Destiné à un usage clinique ? ', 'required' => false))
            ->add('verification_3', 'checkbox', array('label' => 'Nécessite une vérification 3 côtes ?', 'required' => false))
            ->add('assemblage', 'checkbox', array('label' => 'Le produit doit être assemblé ?', 'required' => false))
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
        return 'finortho_fritage_echangebundle_exigence';
    }
}
