<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RelationCreateType extends AbstractType {
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
                ->add( 'vendorEnrollment', new EnrollmentType(), array(
                    'required' => true,
                    'by_reference' => true
                ))
                ->add( 'clientEnrollment', new EnrollmentType(), array(
                    'required' => true,
                    'by_reference' => true
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( array(
            'data_class' => 'TC\CoreBundle\Entity\Relation'
        ) );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_relation_create_form';
    }

}
