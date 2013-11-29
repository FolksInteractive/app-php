<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
            ->add( 'rfp', 'entity', array(
                'class' => 'TC\CoreBundle\Entity\RFP',
                'property' => 'heading',
                'empty_value' => 'Select a corresponding RFP',
                "required" => false,
                "choices" => $options['rfps'],
                "multiple" => false,
                'empty_data'  => null
            ))
            ->add( 'heading' )
            ->add( 'subheading' )
            ->add( 'offer', 'collection', array(
                'type' => 'text_block',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false)
            )
            ->add( 'deliverables', 'collection', array(
                'type' => new DeliverableType(),
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
            'data_class' => 'TC\CoreBundle\Entity\Order',
            'rfps' => array()
        ) );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_order_form';
    }

}
