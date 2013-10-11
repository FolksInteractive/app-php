<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TC\CoreBundle\Entity\Relation;
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
                ->add( 'vendorEnrollment', new EnrollmentType(), array(
                    'required' => true,
                    'by_reference' => true
                ) )
                ->add( 'clientEnrollment', new EnrollmentType(), array(
                    'required' => true,
                    'by_reference' => true
                ) )
        ;

        $builder->addEventListener(
                FormEvents::SUBMIT, function(FormEvent $event) {
                    /** @var Form $form */
                    $form = $event->getForm();
                    
                    /** @var Relation $relation */
                    $relation = $event->getData();
                    
                    // Check if the vendor enrollment or the client enrollment 
                    // has a workspace assigned to it. If not, tries to find it 
                    // by searching with the email address.
                    if( !$relation->getVendorEnrollment()->getWorkspace() ){
                        $workspace = $this->wm->find( $relation->getVendorEnrollment()->getEmail() );
                        $relation->getVendorEnrollment()->setWorkspace($workspace);
                    }
                    
                    if( !$relation->getClientEnrollment()->getWorkspace() ){
                        $workspace = $this->wm->find( $relation->getClientEnrollment()->getEmail() );
                        $relation->getClientEnrollment()->setWorkspace($workspace);
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
