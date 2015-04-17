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

		$searchRequest = new \CPS_SearchRequest(
			$query,
			(isset($data['offset']) ? $data['offset'] : null),
			(isset($data['limit']) ? $data['limit'] : 1000000)
		);

		if ($groupBy)
		{
			$searchRequest->setGroup($groupBy, $groupLimit);
		}

		$searchResponse = $this->connection->sendRequest($searchRequest);
		$clusters = $searchResponse->getRawDocuments(DOC_TYPE_ARRAY);

		return $clusters;
	}
}