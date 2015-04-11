<?php

namespace Frontend\ArticleBundle\Controller;

use Frontend\AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends BaseController
{
	/**
	 * @Route("/api/articles_of_clusters", name="api_articles_of_clusters")
	 */
	public function articlesOfClustersAction(Request $request)
	{
		$articleRepository = $this->get('repository.article');
		$ids = $request->request->get('clusters');
		$articles = $articleRepository->getArticlesOfClusters($ids);

		return new JsonResponse($articles);
	}
}
