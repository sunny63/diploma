<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AdminBaseController
{
    /**
     * @Route("/admin/categories", name="admin_categories")
     */
    public function index()
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Категории';
        $forRender['h1'] = 'Список всех категорий';
        $forRender['categories'] = $category;
        return $this->render("admin/category/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/category/create", name="admin_category_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $category->setCreateAtValue();
            $category->setUpdateAtValue();
            $category->setIsPublished();

            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Категория доабвлена');

            return $this->redirectToRoute('admin_categories');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание категории';
        $forRender['form'] = $form->createView();
        return $this->render("admin/category/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/category/update/{id}", name="admin_category_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            if ($form->get('save')->isClicked())
            {
                $category->setUpdateAtValue();
                $this->addFlash('success', 'Категория обновлена');
            }
            if ($form->get('delete')->isClicked())
            {
                $em->remove($category);
                $this->addFlash('success', 'Категория удалена');
            }

            $em->flush();

            return $this->redirectToRoute('admin_categories');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Обновление категории';
        $forRender['form'] = $form->createView();
        return $this->render("admin/category/form.html.twig", $forRender);
    }

}