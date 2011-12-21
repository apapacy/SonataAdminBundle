<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Command;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Sonata\AdminBundle\Admin\AdminInterface;

class SetupAclCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('sonata:admin:setup-acl');
        $this->setDescription('Install ACL for Admin Classes');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting ACL AdminBundle configuration');

        foreach ($this->getContainer()->get('sonata.admin.pool')->getAdminServiceIds() as $id) {

            try {
                $admin = $this->getContainer()->get($id);
            } catch (\Exception $e) {
                $output->writeln('<error>Warning : The admin class cannot be initiated from the command line</error>');
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
                continue;
            }

            $this->getContainer()->get('sonata.admin.manipulator.acl.admin')->configureAcls($output, $admin);
        }
    }
}