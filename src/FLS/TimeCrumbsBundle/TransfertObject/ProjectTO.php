<?php

namespace FLS\TimeCrumbsBundle\TransfertObject;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectTO {

    protected $options;

    public function __construct( array $options = array() ) {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions( $resolver );

        $this->options = $resolver->resolve( $options );
    }

    protected function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setOptional( array('name', 'description', 'client', 'vendor', 'collaborators') );
        
        $resolver->setDefaults(array(
            "name" => null,
            "description" => null,
            "client" => null,
            "vendor" => null,
            "collaborators" => null
        ));
        
        $resolver->setAllowedTypes( array(
            'client' => array( 'string','integer','null' ),         
            'vendor' => array( 'string','integer','null' ),
            'collaborators' => array('array', 'null')
        ) );
        
    }
    
    /**
     * @Assert\NotNull(groups={"create"})
     */
    public function getName(){
        return $this->options["name"];
    }
    
    /**
     */
    public function getDescription(){
        return $this->options["description"];
    }
    
    public function getClient(){
        return $this->options["client"];
    }
    
    public function getVendor(){
        return $this->options["vendor"];
    }
    
    /**
     * @Assert\True(message = "At least a vendore or a client must be specified.", groups={"create, edit"})
     */
    public function isVendorOrClient(){
        return ($this->getVendor() != null || $this->getClient() != null );
    }
    
    /**
     * @Assert\True(message = "At least a vendore or a client must be specified.", groups={"create, edit"})
     */
    public function isNotTheSame(){
        return $this->getVendor() != $this->getClient();
    }
    
    public function __get($name){
        return $this->options[$name];
    }
    
    public function __set( $name, $value ) {
        return $this->options[$name] = $value;
    }

}

?>
