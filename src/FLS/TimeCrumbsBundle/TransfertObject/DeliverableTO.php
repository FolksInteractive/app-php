<?php

namespace FLS\TimeCrumbsBundle\TransfertObject;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FLS\TimeCrumbsBundle\Validator\Constraints as FLSAssert;

class DeliverableTO {

    protected $options;

    public function __construct( array $options = array() ) {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions( $resolver );

        $this->options = $resolver->resolve( $options );
    }

    protected function setDefaultOptions( OptionsResolverInterface $resolver ) {

        $resolver->setDefaults( array(
            "id" => null,
            "name" => null,
            "description" => null,
            "quantity" => 1,
            "cost" => null
        ) );

        $resolver->setAllowedTypes( array(
            'name' => array('string', 'null'),
            'description' => array('string', 'null'),
            'quantity' => array('integer', 'double','float'),
            'cost' => array('integer','double'),
        ) );
    }

    /**
     * @Assert\NotBlank(groups={"create, edit"})
     */
    public function getName() {
        return $this->options["name"];
    }

    /**
     *      
     * @Assert\Type(
     *     type="integer", 
     *     message="The value {{ value }} is not a valid {{ type }}.",
     *     groups={"create", "edit"}
     * )
     * @Assert\GreaterThan(
     *     value = 0,
     *     groups={"create", "edit"}
     * )
     */
    public function getQuantity() {
        return $this->options["quantity"];
    }
    
    /**
     * @Assert\NotNull(groups={"create, edit"})
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     groups={"create", "edit"}
     * )
     */
    public function getCost() {
        return $this->options["cost"];
    }
    

    public function __get( $name ) {
        return $this->options[$name];
    }

    public function __set( $name, $value ) {
        return $this->options[$name] = $value;
    }

}

?>
