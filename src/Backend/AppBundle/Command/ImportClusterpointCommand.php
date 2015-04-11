<?php

namespace Backend\AppBundle\Command;

use Common\AppBundle\Repository\ClusterpointRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportClusterpointCommand extends ContainerAwareCommand
{
	/**
	 * @var InputInterface
	 */
	protected $input;

	/**
	 * @var OutputInterface
	 */
	protected $output;

	protected function configure()
	{
		$this
			->setName('import:clusterpoint')
			->setDescription('Import clusterpoint')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->import($this->getCategories());
	}

	/**
	 * Get categories.
	 * @return array
	 */
	private function getCategories()
	{
		return [
			[
				'id' => 1,
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Sport1',
				'order' => 1,
			],
			[
				'id' => 2,
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Politics2',
				'order' => 2,
			],
			[
				'id' => 3,
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Animals',
				'order' => 3,
			],
		];
	}

	/**
	 * Import doucments in clusterpoint.
	 * @param array $documents
	 */
	private function import(array $documents)
	{
		$clusterpointRepository = $this->getContainer()->get('repository.clusterpoint');
		$clusterpointRepository->insertOrReplace($documents);
	}
}