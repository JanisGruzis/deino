<?php

namespace UserBundle\Security\Clusterpoint;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{
	protected $clusterpoint;

	public function __construct(\CPS_Connection $clusterpoint)
	{
		$this->clusterpoint = $clusterpoint;
	}

	public function loadUserByUsername($username)
	{
		$query = "<type>user</type><username>$username</username>";
		$documents = $this->clusterpoint->search($query);
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
		return $class === 'UserBundle\Security\Clusterpoint\User';
	}
}