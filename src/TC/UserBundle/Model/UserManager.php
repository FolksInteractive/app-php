<?php

namespace TC\UserBundle\Model;

// choose the appropriate base class depending on your driver


use FOS\UserBundle\Doctrine\UserManager as BasedUserManager;
use TC\UserBundle\Entity\User;

class UserManager extends BasedUserManager
{
}

?>
