<?php

namespace Frontend\ArticleBundle\Controller;

use Frontend\AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClusterController extends BaseController
{
	/**
	 * @Route("/api/grouped_clusters", name="api_grouped_clusters")
	 * @return JsonResponse
	 */
	public function groupedClustersAction()
	{
		$clusterRepository = $this->get('repository.cluster');
		$clusters = $clusterRepository->getCategoryClusters(7);

		return new JsonResponse($clusters);
	}
}