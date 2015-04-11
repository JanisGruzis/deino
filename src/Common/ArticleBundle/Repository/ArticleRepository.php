<?php

namespace Common\ArticleBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class ArticleRepository extends ClusterpointRepository {

	/**
	 * Get articles of clusters.
	 * @return array
	 */
	public function getArticlesOfClusters()
	{
		return [];
	}
}