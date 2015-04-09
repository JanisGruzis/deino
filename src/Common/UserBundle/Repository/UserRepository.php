<?php

namespace Common\UserBundle\Repository;

use Backend\UserBundle\Security\Clusterpoint\User;
use Common\AppBundle\Repository\ClusterpointRepository;

class UserRepository extends ClusterpointRepository {

	protected $passwordEncoder;

	public function __construct(\CPS_Connection $connection, $passwordEncoder)
	{
		parent::__construct($connection);
		$this->passwordEncoder = $passwordEncoder;
	}

	/**
	 * Save user.
	 * @param User $user
	 */
	public function saveUser(User $user)
	{
		$userDocuments = $this->get([
			'type' => self::TYPE_USER,
			'username' => $user->getUsername(),
		]);

		if (!$userDocuments)
		{
			$data = $this->normalizer->normalize($user);
			$data['type'] = self::TYPE_USER;
			$this->insert($data);
		}
	}

	/**
	 * Create user.
	 * @param $username
	 * @param $password
	 * @return User
	 */
	public function createUser($username, $password)
	{
		$salt = $this->createSalt($password);
		$user = new User($username, null, $salt);
		$passwordEncoded = $this->passwordEncoder->encodePassword($user, $password);
		$user->setPassword($passwordEncoded);
		return $user;
	}

	/**
	 * Create salt.
	 * @param $seed
	 * @return string
	 */
	public function createSalt($seed)
	{
		return md5(substr($seed, 0, strlen($seed) / 2) . time());
	}
}