<?php

namespace TC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TCUserBundle extends Bundle
{
    function getParent(){
        return 'FOSUserBundle';
    }
}
