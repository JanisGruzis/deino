<?php

namespace Backend\AppBundle\Command;

use Common\AppBundle\Repository\ClusterpointRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportClusterpointCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('import:clusterpoint')
			->setDescription('Import clusterpoint')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->import(self::getCategories());
		$this->import(self::getSources());
	}

	/**
	 * Get sources.
	 * @return array
	 */
	public static function getSources()
	{
		return [
			[
				'id' => 'Diena',
				'type' => ClusterpointRepository::TYPE_SOURCE,
				'name' => 'Diena',
			],
			[
				'id' => 'LSM',
				'type' => ClusterpointRepository::TYPE_SOURCE,
				'name' => 'LSM',
			],
			[
				'id' => 'Apollo',
				'type' => ClusterpointRepository::TYPE_SOURCE,
				'name' => 'Apollo',
			],
			[
				'id' => 'Delfi',
				'type' => ClusterpointRepository::TYPE_SOURCE,
				'name' => 'Delfi',
			],
			[
				'id' => 'TVNET',
				'type' => ClusterpointRepository::TYPE_SOURCE,
				'name' => 'TVNET',
			],
		];
	}

	/**
	 * Get categories.
	 * @return array
	 */
	public static function getCategories()
	{
		return [
			[
				'id' => 'local',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Latvija',
				'order' => 1,
			],
			[
				'id' => 'business',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Bizness',
				'order' => 2,
			],
			[
				'id' => 'abroad',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Ārzemēs',
				'order' => 3,
			],
			[
				'id' => 'sport',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Sports',
				'order' => 4,
			],
			[
				'id' => 'culture',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Kultūra',
				'order' => 5,
			],
			[
				'id' => 'car',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Auto',
				'order' => 6,
			],
			[
				'id' => 'technology',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Tehnoloģijas',
				'order' => 7,
			],
			[
				'id' => 'entertainment',
				'type' => ClusterpointRepository::TYPE_CATEGORY,
				'name' => 'Izklaide',
				'order' => 8,
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