<?php

namespace Common\AppBundle\Repository;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ClusterpointRepository {

	const TYPE_USER = 'user';
	const TYPE_ARTICLE = 'article';
	const TYPE_CLUSTER = 'cluster';
	const TYPE_CATEGORY = 'category';

	/**
	 * @var \CPS_Connection
	 */
	protected $connection;

	/**
	 * @var \CPS_Simple
	 */
	protected $simple;

	/**
	 * @var Serializer
	 */
	protected $serializer;

	/**
	 * @var GetSetMethodNormalizer
	 */
	protected $normalizer;

	/**
	 * @param \CPS_Connection $connection
	 */
	public function __construct(\CPS_Connection $connection)
	{
		$this->connection = $connection;
		$this->simple = new \CPS_Simple($connection);

		$encoders = array(new XmlEncoder(), new JsonEncoder());
		$this->normalizer = new GetSetMethodNormalizer();
		$this->serializer = new Serializer([$this->normalizer], $encoders);
	}

	/**
	 * @return \CPS_Connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * Get documents.
	 * @param array $data
	 * @return array Documents
	 */
	public function get(array $data)
	{
		return $this->simple->search($data);
	}

	/**
	 * Insert document.
	 * @param $data
	 * @return int
	 */
	public function insert($data)
	{
		return $this->simple->insertSingle(time() . rand(), $data);
	}

	/**
	 * @param array $terms
	 * @return string
	 */
	public function _or(array $terms)
	{
		return sprintf('{%s}', implode(' ', $terms));
	}

	/**
	 * @param array $terms
	 * @return string
	 */
	public function _and(array $terms)
	{
		return sprintf('(%s)', implode(' ', $terms));
	}

	/**
	 * @param $term
	 * @return string
	 */
	public function _not($term)
	{
		return sprintf('~%s', $term);
	}
}