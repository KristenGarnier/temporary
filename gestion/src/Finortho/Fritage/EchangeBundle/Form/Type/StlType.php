<?php

namespace Finortho\Fritage\EchangeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StlType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('label' => 'Votre fichier .stl'))
            ->add('name', 'text', array('label' => 'Nom du fichier'))
            ->add('commentaire', 'textarea', array('label' => 'Dites en nous plus sur le fichier'))
            ->add('quantite', 'number', array('label' => 'Le nombre de pièces à produire'))
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
        return 'finortho_fritage_echangebundle_stl';
    }
}
