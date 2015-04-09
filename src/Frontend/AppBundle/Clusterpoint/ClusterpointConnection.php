<?php

namespace Frontend\AppBundle\Clusterpoint;

class ClusterpointConnection extends \CPS_Connection {

	public function __construct($name, $password, $address, $id, $database)
	{
		parent::__construct(
			new \CPS_LoadBalancer($address),
			$database,
			$name,
			$password,
			'document',
			'//document/id',
			array('account' => $id)
		);
	}
}