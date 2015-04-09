<?php

namespace Backend\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/drosiba")
 */
class DrosibaController extends Controller
{
    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
		$authenticationUtils = $this->get('security.authentication_utils');
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

        return [
			'error' => $error,
			'lastUsername' => $lastUsername,
		];
    }

	/**
	 * @Route("/login_check", name="drosiba_login_check")
	 */
	public function loginCheckAction()
	{
		return new Response('');
	}
}
