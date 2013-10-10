<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PurchaseType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
            ->add( 'approved', 'checkbox' )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( array(
            'data_class' => 'TC\CoreBundle\Entity\Order'
        ) );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_purchase_form';
    }

}
