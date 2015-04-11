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
		$ids1 = $request->request->get('clusters', []);
		$ids2 = $request->query->get('clusters', []);
		$ids = array_merge(is_array($ids1) ? $ids1 : [], is_array($ids2) ? $ids2 : []);
		$articles = $articleRepository->getArticlesOfClusters($ids);

		return new JsonResponse($articles);
	}
}
