<?php

namespace Common\ArticleBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class ArticleRepository extends ClusterpointRepository {

	/**
	 * Get articles.
	 * @param array $data
	 * @return array
	 */
	public function getArticles(array $data = [])
	{
		$xmlQuery = '';
		$query = [ 'type' => self::TYPE_ARTICLE ];

		if (isset($data['period']))
		{
			$period = $data['period'];
			if (in_array($period, ['day', 'week', 'month']))
			{
				$time = strtotime('-1 ' . $period);
				$query['date'] = $this->_ge(date('Y/m/d H:i:s', $time));
			}
		}

		if (isset($data['sources']) and is_array($data['sources']) and $data['sources'])
		{
			$query['source'] = $this->_or($data['sources']);
		}

		if (isset($data['query']))
		{
			$q = $this->_stemming($data['query']);
			$xmlQuery = '{<description>'.$q.'</description><title>'.$q.'</title>}';
		}

		$fullQuery = CPS_QueryArray($query) . $xmlQuery;
		$searchRequest = new \CPS_SearchRequest(
			$fullQuery,
			(isset($data['offset']) ? $data['offset'] : null),
			(isset($data['limit']) ? $data['limit'] : 1000000)
		);
		$searchRequest->setOrdering([CPS_RelevanceOrdering('desc'), CPS_DateOrdering('date', 'desc')]);
		$searchResponse = $this->connection->sendRequest($searchRequest);
		$articles = $searchResponse->getRawDocuments(DOC_TYPE_ARRAY);

		return $articles;
	}

	/**
	 * Get articles of clusters.
	 * @param array $data
	 * @return array
	 */
	public function getArticlesOfClusters(array $data = [])
	{
		$query = [
			'type' => self::TYPE_ARTICLE,
			'cluster_id' => $this->_or((isset($data['clusters']) ? $data['clusters'] : [])),
		];

		if (isset($data['sources']) and is_array($data['sources']) and $data['sources'])
		{
			$query['source'] = $this->_or($data['sources']);
		}

		$searchRequest = new \CPS_SearchRequest(
			$query,
			(isset($data['offset']) ? $data['offset'] : null),
			(isset($data['limit']) ? $data['limit'] : 1000000)
		);

		$searchRequest->setOrdering([
			CPS_RelevanceOrdering('descending'),
			CPS_DateOrdering('date', 'descending'),
		]);

		$searchResponse = $this->connection->sendRequest($searchRequest);
		$articles = $searchResponse->getRawDocuments(DOC_TYPE_ARRAY);

		return $articles;
	}
}