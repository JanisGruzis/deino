<?php

namespace Frontend\ArticleBundle\Controller;

use Frontend\AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends BaseController
{
	/**
	 * @Route("/api/image", name="api_image")
	 */
	public function imageAction(Request $request)
	{
		$liipManager = $this->container->get('liip_imagine.controller');
		$imageUrl = $request->query->get('path');
		$webPath = realpath($this->get('kernel')->getRootDir() . '/../web/images/');

		$headers = get_headers($imageUrl);
		$urlInfo = parse_url($imageUrl);
		$fname = basename($urlInfo['path']);
		$fpath = $webPath . '/' . $fname;

		if (!file_exists($fpath)) {
			$content = file_get_contents($imageUrl);
			if (!$content) {
				throw $this->createNotFoundException('Image not found.');
			}
			file_put_contents($fpath, $content);
		}

		return $liipManager->filterAction(
			$request,
			'images/' . $fname,
			'mobile_image'
		);
	}
}
