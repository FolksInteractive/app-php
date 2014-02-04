<?php
namespace TC\CoreBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Form\WorkspaceTransformer;
use TC\CoreBundle\Model\WorkspaceManager;

class WorkspaceType extends AbstractType {
    /**
     * @var WorkspaceManager
     */
    private $wm;
    
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param ObjectManager $om
     */
    public function __construct( WorkspaceManager $wm, Validator $validator )
    {
        $this->wm = $wm;
        $this->validator = $validator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new WorkspaceTransformer($this->wm);
        $builder->addModelTransformer($transformer);
        
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function( FormEvent $event ){
            $form = $event->getForm();
            $data = $event->getData();
            
            $errors = $this->validator->validateValue( $data, new EmailConstraint() );
            
            
            if( count($errors) > 0 )
                $form->addError( new FormError( $errors[0] ) );
            
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected workspace does not exist',
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'workspace';
    }
}

?>
