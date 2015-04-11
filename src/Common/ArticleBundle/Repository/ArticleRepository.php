<?php

namespace Common\ArticleBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class ArticleRepository extends ClusterpointRepository {

	/**
	 * Get articles of clusters.
	 * @param array $ids
	 * @return array
	 */
	public function getArticlesOfClusters(array $ids)
	{
		$documents = $this->get([
			'type' => self::TYPE_ARTICLE,
			'cluster_id' => $this->_or($ids),
		]);

		$articles = [];
		foreach ($documents as $document)
		{
			$clusterId = $document['cluster_id'];
			if (!isset($articles[$clusterId]))
			{
				$articles[$clusterId] = [];
			}

			$articles[$clusterId][] = $document;
		}

		return $articles;
	}
}