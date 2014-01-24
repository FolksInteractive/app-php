<?php

namespace TC\CoreBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Model\WorkspaceManager;

class WorkspaceTransformer implements DataTransformerInterface {

    /**
     * @var WorkspaceManager
     */
    private $wm;

    /**
     * @param WorkspaceManager $om
     */
    public function __construct( WorkspaceManager $wm ){
        $this->wm = $wm;
    }

    /**
     * Transforms an workspace to an email.
     *
     * @param  Workspace|null $workspace
     * @return string
     */
    public function transform( $workspace ){
        if( null === $workspace || $workspace instanceof Workspace){
            return "";
        }

        return $workspace->getEmail();
    }

    /**
     * Transforms a string (email) to a Workspace.
     *
     * @param  string $email
     *
     * @return Workspace|null
     *
     * @throws TransformationFailedException if workspace is not found.
     */
    public function reverseTransform( $email ){
        if( !$email ){
            return null;
        }

        return $this->wm->find($email);
    }

}

?>
