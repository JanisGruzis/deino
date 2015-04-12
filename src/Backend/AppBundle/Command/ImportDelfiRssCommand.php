<?php

namespace Backend\AppBundle\Command;

use Common\AppBundle\Repository\ClusterpointRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDelfiRssCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('import:delfi:rss')
			->setDescription('Import from delfi rss')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$url = 'http://www.delfi.lv/rss.php';
		$xml = new \SimpleXMLElement($url, 0, true);
		$clusterpointRepository = $this->getContainer()->get('repository.clusterpoint');


		foreach ($xml->channel->item as $item)
		{
			$articleId = uniqid() . rand();
			$clusterId = uniqid() . rand();
			$categoryIndex = array_rand(ImportClusterpointCommand::getCategories());
			$category = ImportClusterpointCommand::getCategories()[$categoryIndex];
			$categoryId = $category['id'];

			$article = [
				'id' => $articleId,
				'type' => ClusterpointRepository::TYPE_ARTICLE,
				'cluster_id' => $clusterId,
				'title' => strip_tags($item->title),
				'description' => strip_tags($item->description),
				'image' => 'http://www.cse.unsw.edu.au/opencms/export/sites/cse/.content/images/feature_boxes_750x400/news_events.jpg_687647283.jpg',
				'rating' => rand(1, 10),
				'url_mobile' => trim($item->link),
				'url' => trim($item->link),
				'source' => 'delfi.lv',
				'date' => (new \DateTime($xml->pubDate))->format('Y-m-d H:i:s'),
				'tokens' => 'one, two, three',
			];

			$cluster = [
				'id' => $clusterId,
				'type' => ClusterpointRepository::TYPE_CLUSTER,
				'category_id' => $categoryId,
				'first_date' => (new \DateTime($xml->pubDate))->format('Y-m-d H:i:s'),
				'last_date' => (new \DateTime($xml->pubDate))->format('Y-m-d H:i:s'),
				'size' => 1,
			];

			$clusterpointRepository->insert($article);
			$clusterpointRepository->insert($cluster);
		}
	}
}