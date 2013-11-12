<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
                ->add( 'name' )
                ->add( 'description', 'textarea', array('required' => false ))
                ->add( 'objectives', 'collection', array(
                    'required' => false,
                    'type' => new ProjectObjectiveType(),
                    'allow_add' => true));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( array(
            'data_class' => 'TC\CoreBundle\Entity\Project'
        ) );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_project_form';
    }

}
