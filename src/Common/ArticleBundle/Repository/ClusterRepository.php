<?php

namespace Common\ArticleBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class ClusterRepository extends ClusterpointRepository {

	/**
	 * Get category clusters.
	 * @param $data
	 * @return mixed
	 */
	public function getClusters(array $data = [], $groupBy = null, $groupLimit = null)
	{
		$query = [
			'type' => self::TYPE_CLUSTER,
		];

		if (isset($data['categories']) and is_array($data['categories']))
		{
			$query['category_id'] = $this->_or($data['categories']);
		}

		if (isset($data['sources']) and is_array($data['sources']))
		{
			$query['source'] = $this->_or($data['sources']);
		}

		$searchRequest = new \CPS_SearchRequest(
			$query,
			(isset($data['offset']) ? $data['offset'] : null),
			(isset($data['limit']) ? $data['limit'] : 1000000)
		);

		$searchRequest->setOrdering([
			CPS_DateOrdering('first_date', 'descending'),
			CPS_DateOrdering('last_date', 'descending'),
			CPS_RelevanceOrdering('descending')
		]);

		if ($groupBy)
		{
			$searchRequest->setGroup($groupBy, $groupLimit);
		}

		$searchResponse = $this->connection->sendRequest($searchRequest);
		$clusters = $searchResponse->getRawDocuments(DOC_TYPE_ARRAY);

		return $clusters;
	}
}