<?php

namespace Backend\UserBundle\Security\Clusterpoint;

use Common\UserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{
	/**
	 * @var UserRepository
	 */
	protected $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function loadUserByUsername($username)
	{
		$documents = $this->userRepository->get([
			'type' => UserRepository::TYPE_USER,
			'username' => $username,
		]);
		var_dump($documents);
		$userData = reset($documents);

        if ($userData) {
			$username = $userData['username'];
			$password = $userData['password'];
			$salt = $userData['salt'];
			$roles = $userData['roles'];

			return new User($username, $password, $salt, $roles);
		}

        throw new UsernameNotFoundException(
			sprintf('Username "%s" does not exist.', $username)
		);
    }

	public function refreshUser(UserInterface $user)
	{
		if (!$user instanceof WebserviceUser) {
			throw new UnsupportedUserException(
				sprintf('Instances of "%s" are not supported.', get_class($user))
			);
		}

		return $this->loadUserByUsername($user->getUsername());
	}

	public function supportsClass($class)
	{
		return $class === 'Backend\UserBundle\Security\Clusterpoint\User';
	}
}