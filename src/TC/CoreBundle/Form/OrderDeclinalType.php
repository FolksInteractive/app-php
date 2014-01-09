<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderDeclinalType extends AbstractType {
    private $choices = array(
        "It doesn't apply anymore.",
        "It is too late now.",
        "There was a misunderstanding in the requierement or clauses.",
        "This is to expensive for my budget."
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
                /** @var Form $form */
                $form = $event->getForm();

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
        return 'tc_order_declinal_form';
    }

}
