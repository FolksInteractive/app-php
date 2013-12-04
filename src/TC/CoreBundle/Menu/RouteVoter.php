<?php
namespace TC\CoreBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Voter based on the uri
 */
class RouteVoter implements VoterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param  ItemInterface $item
     * @return boolean|null
     */
    public function matchItem(ItemInterface $item)
    {
        $uri = $this->container->get('request')->getRequestUri();
        if ($item->getUri() === $uri) {
            return true;
        } else {
            if ($item->getUri() !== '/' && $item->getUri() !== '/app_dev.php/' && (strpos( $uri, $item->getUri()) === 0)) {
                return true;
            }
        }
 
        return null;
    }
}