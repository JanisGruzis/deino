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
		return $this->simple->search($data, null, null, null, null, DOC_TYPE_ARRAY	);
	}

	/**
	 * Insert document.
	 * @param array $document
	 * @return int
	 */
	public function insert(array $document)
	{
		$id = isset($document['id']) ? $document['id'] : $this->getUniqueId();
		return $this->simple->insertSingle($id, $document);
	}

	/**
	 * @param array $documents
	 * @return int
	 */
	public function insertMultiple(array $documents)
	{
		return $this->simple->insertMultiple($documents);
	}

	/**
	 * Replace document.
	 * @param array $document
	 * @return int
	 */
	public function replace(array $document)
	{
		return $this->simple->replaceSingle($document['id'], $document);
	}

	/**
	 * @param array $documents
	 * @return int
	 */
	public function replaceMultiple(array $documents)
	{
		return $this->simple->replaceMultiple($documents);
	}

	/**
	 * If exists, replace else insert.
	 * @param array $documents
	 */
	public function insertOrReplace(array $documents)
	{
		foreach ($documents as $document)
		{
			$id = isset($document['id']) ? $document['id'] : $this->getUniqueId();
			$resDocuments = $this->get(['id' => $id, 'type' => $document['type']]);
			if ($resDocuments)
			{
				$this->replace($document);
			} else {
				$this->insert($document);
			}
		}
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

	/**
	 * Returns unique id.
	 * @return string
	 */
	public function getUniqueId()
	{
		return uniqid() . rand();
	}
}