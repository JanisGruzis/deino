<?php

namespace Backend\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('create:user')
			->setDescription('Create user')
			->addArgument(
				'username',
				InputArgument::REQUIRED,
				'Username'
			)
			->addArgument(
				'password',
				InputArgument::REQUIRED,
				'Password'
			)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$username = $input->getArgument('username');
		$password = $input->getArgument('password');
		$userRepository = $this->getContainer()->get('repository.user');

		$user = $userRepository->createUser($username, $password);
		$userRepository->saveUser($user);

		$output->writeln('User created.');
	}
}