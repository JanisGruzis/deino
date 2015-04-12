<?php
namespace Backend\UserBundle\Security\Clusterpoint;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class User implements UserInterface, EquatableInterface
{
	private $username;
	private $password;
	private $salt;
	private $roles;

	public function __construct($username, $password, $salt, array $roles = [])
	{
		$this->username = $username;
		$this->password = $password;
		$this->salt = $salt;
		$this->roles = json_encode($roles);
	}

	public function getRoles()
	{
		return json_decode($this->roles, true);
	}

	public function setRoles(array $roles)
	{
		$this->roles = json_encode($roles);
		return $this;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	public function getSalt()
	{
		return $this->salt;
	}

	public function setSalt($salt)
	{
		$this->salt = $salt;
		return $this;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	public function eraseCredentials()
	{
	}

	public function isEqualTo(UserInterface $user)
	{
		if (!$user instanceof WebserviceUser) {
			return false;
		}

		if ($this->password !== $user->getPassword()) {
			return false;
		}

		if ($this->salt !== $user->getSalt()) {
			return false;
		}

		if ($this->username !== $user->getUsername()) {
			return false;
		}

		return true;
	}
}