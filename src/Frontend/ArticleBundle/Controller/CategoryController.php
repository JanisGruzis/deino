<?php

namespace Frontend\ArticleBundle\Controller;

use Frontend\AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends BaseController
{
	/**
	 * @Route("/api/categories", name="api_categories")
	 */
	public function getCategoriesAction()
	{
		$categroyRepository = $this->get('repository.category');
		$categories = $categroyRepository->getCategories();

		return new JsonResponse($categories);
	}
}
