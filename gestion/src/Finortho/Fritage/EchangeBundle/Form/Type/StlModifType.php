<?php

namespace Finortho\Fritage\EchangeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StlModifType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Nom du fichier'))
            ->add('commentaire', 'textarea', array(
                'label' => 'Dites en nous plus sur le fichier',
                'required' => false
            ))
            ->add('quantite', 'number', array('label' => 'Le nombre de pièces à produire'))
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

    public function configureOptions(OptionsResolver $resolver)
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
        return 'finortho_fritage_echangebundle_stl_modif';
    }
}
