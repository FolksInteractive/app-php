<?php

namespace FLS\TimeCrumbsBundle\TransfertObject;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FLS\TimeCrumbsBundle\Validator\Constraints as FLSAssert;

class OrderTO {

    protected $options;

    public function __construct( array $options = array() ) {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions( $resolver );

        $this->options = $resolver->resolve( $options );
    }

    protected function setDefaultOptions( OptionsResolverInterface $resolver ) {

        $resolver->setDefaults( array(
            "request" => null,
            "offer" => null,
            "active" => null,
            "deliverables" => null
        ) );

        $resolver->setAllowedTypes( array(
            'request' => array('string', 'null'),
            'offer' => array('string', 'null'),
            'active' => array('boolean', 'null'),
            'deliverables' => array('array', 'null')
        ) );
    }

    /**
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Null(message="You cannot change the request description after creation", groups={"edit"})
     */
    public function getRequest() {
        return $this->options["request"];
    }

    /**
     * @FLSAssert\NotBlankButNullable(groups={"create", "edit"})
     */
    public function getOffer() {
        return $this->options["offer"];
    }

    /* @Assert\True(message = "A request or offer must be at least defined", groups={"create, edit"})
     */
    public function hasRequestOrOffer() {
        return ($this->offer != null || $this->request != null );
    }

    public function __get( $name ) {
        return $this->options[$name];
    }

    public function __set( $name, $value ) {
        return $this->options[$name] = $value;
    }

}

?>
