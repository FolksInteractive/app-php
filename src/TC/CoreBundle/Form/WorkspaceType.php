<?php
namespace TC\CoreBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TC\CoreBundle\Form\WorkspaceTransformer;
use TC\CoreBundle\Model\WorkspaceManager;

class WorkspaceType extends AbstractType {
    /**
     * @var WorkspaceManager
     */
    private $wm;

    /**
     * @param ObjectManager $om
     */
    public function __construct( WorkspaceManager $wm)
    {
        $this->wm = $wm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new WorkspaceTransformer($this->wm);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected workspace does not exist',
        ));
    }

    public function getParent()
    {
        return 'email';
    }

    public function getName()
    {
        return 'workspace';
    }
}

?>
