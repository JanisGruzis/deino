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
		$searchRequest = new \CPS_SearchRequest(
			[ 'type' => self::TYPE_CATEGORY ], null, 1000000
		);

		$searchRequest->setOrdering([
			CPS_StringOrdering('name', 'lv')
		]);

		$searchResponse = $this->connection->sendRequest($searchRequest);
		$categories = $searchResponse->getRawDocuments(DOC_TYPE_ARRAY);

		return $categories;
	}
}