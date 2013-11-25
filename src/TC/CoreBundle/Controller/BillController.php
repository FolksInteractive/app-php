<?php

namespace TC\CoreBundle\Controller;

use TC\CoreBundle\Model\BillManager;

class BillController extends Controller {
       
    /**
     * @return BillManager
     */
    protected function getBillManager(){
        return $this->container->get('tc.manager.bill');
    }
}