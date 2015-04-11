<?php

namespace Common\ArticleBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class CategoryRepository extends ClusterpointRepository {

	/**
	 * Get categories.
	 * @return array
	 */
	public function getCategories()
	{
		return $this->get(['type' => self::TYPE_CATEGORY]);
	}
}