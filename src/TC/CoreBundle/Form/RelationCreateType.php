<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Model\WorkspaceManager;

class RelationCreateType extends AbstractType {
    
    /** @var WorkspaceManager */
    private $wm;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $this->wm = $options["wm"];
        
        $builder
                ->add( 'vendor', 'email', array("mapped" => false))
                ->add( 'client', 'email', array("mapped" => false))
        ;

        // This EventListener is for trying to find an 
        // existing Workspace for the submitted email
        $builder->addEventListener(
                FormEvents::SUBMIT, function(FormEvent $event) {
                    /** @var Form $form */
                    $form = $event->getForm();
                    
                    /** @var Relation $relation */
                    $relation = $event->getData();
                    
                    //$workspace = $this->wm->find( $relation->getVendor()->getEmail() );
                    
                    if( $form->has("vendor") ){
                        $vendorType = $form->get("vendor");
                        $vendorEmail = $vendorType->getData();
                        
                        $workspace = $this->wm->find( $vendorEmail );
                        
                        if( $workspace == null ){
                            $workspace = $this->wm->createTemporary( $vendorEmail );
                        }
                        
                        $relation->setVendor($workspace);
                    }
                    
                    if( $form->has("client") ){
                        $clientType = $form->get("client");
                        $clientEmail = $clientType->getData();
                        
                        $workspace = $this->wm->find( $clientEmail );
                                
                        if( $workspace == null ){
                            $workspace = $this->wm->createTemporary( $clientEmail );
                        }
                        
                        $relation->setClient($workspace);
                    }
                    
                }
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults( array(
            'data_class' => 'TC\CoreBundle\Entity\Relation'
        ) );
        
        $resolver->setRequired(array(
            'wm',
        ));

        $resolver->setAllowedTypes(array(
            'wm' => 'TC\CoreBundle\Model\WorkspaceManager',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tc_relation_create_form';
    }

}
