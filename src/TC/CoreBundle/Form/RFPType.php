<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RFPType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
            ->add( 'heading' )
            ->add( 'subheading' )
            ->add( 'body', 'collection', array(
                'type' => 'text_block',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false)
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( array(
            'data_class' => 'TC\CoreBundle\Entity\RFP'
        ) );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_rfp_form';
    }

}
