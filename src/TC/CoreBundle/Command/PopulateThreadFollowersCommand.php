<?php

namespace TC\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Thread;

class PopulateThreadFollowersCommand extends ContainerAwareCommand {

    protected function configure() {
        parent::configure();
        $this
                ->setName( 'populate:thread:followers' )
                ->setDescription( 'Setting followers to existing threads' )
        ;
    }

    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * 
     * @var \TC\CoreBundle\Entity\Relation $relation
     */
    protected function execute( InputInterface $input, OutputInterface $output ) {
        $output->writeln( "..." );
        $doctrine = $this->getContainer()->get( 'doctrine' );
        $em = $doctrine->getEntityManager();

        $orders = $doctrine->getRepository( 'TCCoreBundle:Order' )->findAll();
        foreach ( $orders as $order ) {
            $thread = $order->getThread();
            if ( $thread->getFollowers()->count() === 0 ) {
                $thread->addFollower( $order->getRelation()->getClient() );
                $thread->addFollower( $order->getRelation()->getVendor() );
                $em->persist( $thread );
            }
        }
        $em->flush();

        $output->writeln( "done." );
    }

}

?>
