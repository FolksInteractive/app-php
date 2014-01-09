<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RFPDeclinalType extends AbstractType {
    private $choices = array(
        "This is not part of my expertise.",
        "I do not have enough resources to meet this demand.",
        "I do not have the time to work on it.",
        "What is requested is in progress or is already done."
    );
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
            ->add( 'why', 'choice', array(
                'choices' => $this->choices,
                'expanded' => true,
            ) )

            ->add( 'other', 'textarea', array( "required" => false ) )
            
            ->addEventListener( FormEvents::SUBMIT, function(FormEvent $event){

                $declinal = $event->getData();
                
                if( $declinal["why"] ){
                    $key = $declinal["why"]; 
                    $declinal["why"] = $this->choices[$key];
                }
                
                $event->setData($declinal);
            })
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( array(
            'data_class' => null,
        ) );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_rfp_declinal_form';
    }

}
