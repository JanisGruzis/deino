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
		$query = [ 'type' => self::TYPE_ARTICLE ];

		if (isset($data['to']))
		{
			$time = strtotime($data['to']);
			$query['date'] = $this->_lt(date('Y/m/d H:i:s', $time));
		}

		if (isset($data['sources']) and is_array($data['sources']))
		{
			$query['source'] = $this->_or($data['sources']);
		}

		if (isset($data['query']) and is_array($data['query']))
		{
			$q = $this->_stemming($data['sources']);
			$query['description'] = $this->_like($q);
		}

		$searchRequest = new \CPS_SearchRequest(
			$query,
			(isset($data['offset']) ? $data['offset'] : null),
			(isset($data['limit']) ? $data['limit'] : 1000000)
		);
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

		$searchRequest = new \CPS_SearchRequest(
			$query,
			(isset($data['offset']) ? $data['offset'] : null),
			(isset($data['limit']) ? $data['limit'] : 1000000)
		);
		$searchResponse = $this->connection->sendRequest($searchRequest);
		$articles = $searchResponse->getRawDocuments(DOC_TYPE_ARRAY);

		return $articles;
	}
}