<?php

namespace Frontend\ArticleBundle\Controller;

use Frontend\AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends BaseController
{
	/**
	 * @Route("/api/categories", name="api_categories")
	 */
	public function categoriesAction()
	{
		$categroyRepository = $this->get('repository.category');
		$categories = $categroyRepository->getCategories();

		return new JsonResponse($categories);
	}

	/**
	 * @Route("/api/sources", name="api_sources")
	 */
	public function sourcesAction()
	{
		$sourceRepository = $this->get('repository.source');
		$sources = $sourceRepository->getSources();

		return new JsonResponse($sources);
	}

	/**
	 * @Route("/api/popular/{period}", name="api_popular")
	 */
	public function sourcesAction($period)
	{
		$test = [$period];

		return new JsonResponse($test);
	}

	/**
	 * @Route("/api/articles_by_category", name="api_articles_by_category")
	 */
	public function articlesByCategoryAction(Request $request)
	{
		$articleRepository = $this->get('repository.article');
		$clusterRepository = $this->get('repository.cluster');

		$clusters = $clusterRepository->getClusters([], 'category_id', 3);
		$clusterIds = array_map(function($item){
			return $item['id'];
		}, $clusters);

		$articles = $articleRepository->getArticlesOfClusters($clusterIds);
		$articleMap = $this->mapArticles($articles);

		$result = [];
		foreach ($clusters as $cluster)
		{
			$cluster['articles'] = (isset($articleMap[$cluster['id']]) ? $articleMap[$cluster['id']] : []);
			if (!isset($result[$cluster['category_id']]))
			{
				$result[$cluster['category_id']] = [];
			}
			$result[$cluster['category_id']][] = $cluster;
		}

		return new JsonResponse($result);
	}

	/**
	 * @Route("/api/category_clusters", name="api_category_clusters")
	 */
	public function categoryClustersAction(Request $request)
	{
		$articleRepository = $this->get('repository.article');
		$clusterRepository = $this->get('repository.cluster');

		$query = $request->query->all();
		$clusters = $clusterRepository->getClusters($query);
		$clusterIds = array_map(function($item){
			return $item['id'];
		}, $clusters);

		$articles = $articleRepository->getArticlesOfClusters([
			'clusters' => $clusterIds
		]);
		$articleMap = $this->mapArticles($articles);

		foreach ($clusters as $key => $cluster) {
			$clusters[$key]['articles'] = (isset($articleMap[$cluster['id']]) ? $articleMap[$cluster['id']] : []);
		}

		return new JsonResponse($clusters);
	}

	/**
	 * @Route("/api/articles", name="api_articles")
	 */
	public function articlesAction(Request $request)
	{
		$articleRepository = $this->get('repository.article');
		$query = $request->query->all();
		$articles = $articleRepository->getArticles($query);

		return new JsonResponse($articles);
	}

	/**
	 * @param $articles
	 * @return array
	 */
	private function mapArticles($articles)
	{
		$articleMap = [];
		foreach ($articles as $article)
		{
			if (!isset($articleMap[$article['cluster_id']]))
			{
				$articleMap[$article['cluster_id']] = [];
			}
			$articleMap[$article['cluster_id']][] = $article;
		}

		return $articleMap;
	}
}
