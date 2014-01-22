<?php

namespace TC\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller {

    /**
     * Display the dashboard
     *
     * @Route("/", name="dashboard")
     * @Method("GET")
     * @Template("TCCoreBundle::dashboard.html.twig")
     */
    public function rootAction( Request $request ){

        // Retreive remember cookie information to know where the user was last time he was in the app.
        $remember = json_decode( $request->cookies->get( "remember" ) );
        
        $remember = array();
        if( $request->cookies->has( 'remember' ) )
            $remember = json_decode( $request->cookies->get( 'remember' ), true );
            
        if( isset($remember['was']) ){
            switch( $remember['was'] ){
                case 'vendor':
                    return $this->redirect( $this->generateUrl( "client_overview" ) );

                case 'client':
                    return $this->redirect( $this->generateUrl( "vendor_overview" ) );
            }
        }

        return array( );
    }

}
