<?php

namespace Common\ArticleBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class SourceRepository extends ClusterpointRepository {

	/**
	 * Get sources.
	 * @return array
	 */
	public function getSources()
	{
		$searchRequest = new \CPS_SearchRequest(
			[ 'type' => self::TYPE_SOURCE ], null, 1000000
		);

		$searchRequest->setOrdering([
			CPS_StringOrdering('name', 'lv')
		]);

		$searchResponse = $this->connection->sendRequest($searchRequest);
		$sources = $searchResponse->getRawDocuments(DOC_TYPE_ARRAY);

		return $sources;
	}
}