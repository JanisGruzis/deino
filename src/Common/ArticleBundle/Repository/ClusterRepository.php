<?php

namespace Common\ArticleBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class ClusterRepository extends ClusterpointRepository {

	/**
	 * Get category clusters.
	 * @return array
	 */
	public function getCategoryClusters()
	{
		return [];
	}
}