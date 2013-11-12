<?php

namespace TC\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Thread;
use TC\CoreBundle\Entity\ContactList;
use TC\CoreBundle\Entity\Contact;

class PopulateContactListCommand extends ContainerAwareCommand {

    protected function configure() {
        parent::configure();
        $this
                ->setName( 'populate:contacts' )
                ->setDescription( 'Setting a contact list to workspaces without one' )
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

        $workspaces = $doctrine->getRepository( 'TCCoreBundle:Workspace' )->findAll();
        foreach ( $workspaces as $workspace ) {
            $workspace->setContactList(new ContactList());
            $em->persist( $workspace );
        }
        $em->flush();

        $output->writeln( "done." );
    }

}

?>
