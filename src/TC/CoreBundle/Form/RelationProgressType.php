<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RelationProgressType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
            ->add('deliverables', 'collection', array(
                'required'  => false,                
                'type'      => new DeliverableProgressType(),
                'data'      => $options["deliverables"],
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( array(
            'data_class' => null,
            'deliverables' => array()
        ) );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_progress_form';
    }

}
