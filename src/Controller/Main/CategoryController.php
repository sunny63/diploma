<?php


namespace App\Controller\Main;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Stock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    /**
     * @Route("/category/{id}", name="main_category")
     * @param int $id
     * @param Request $request
     */
    public function index(Request $request, int $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $dateWithoutDays = (new \DateTime('now'))->modify('- 20 days');
        $newPosts = $this->getDoctrine()->getRepository(Post::class)->findNewPosts($dateWithoutDays);

        $forRender = parent::renderDefault();
        $forRender['today'] = new \DateTime('now');
        $forRender['title'] = 'Категория акций';
        $forRender['category'] = $category;
        $forRender['new_posts'] = $newPosts;
        return $this->render("main/category/index.html.twig", $forRender);
    }

    /**
     * @Route("/category/{id}/stocks", name="main_category_stocks")
     * @param int $id
     * @param Request $request
     */
    public function showPhotoReports(int $id, Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $stocks = $category->getStocks();

        $dateWithoutDays = (new \DateTime('now'))->modify('- 20 days');
        $newPosts = $this->getDoctrine()->getRepository(Post::class)->findNewPosts($dateWithoutDays);

        $forRender = parent::renderDefault();
        $forRender['category'] = $category;
        $forRender['new_posts'] = $newPosts;
        $forRender['stocks'] = $stocks;
        return $this->render("main/category/stocks/index.html.twig", $forRender);
    }
}