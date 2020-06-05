<?php


namespace App\Controller\Admin;

use App\Entity\Child;
use App\Entity\Post;
use App\Entity\Stock;
use App\Form\ChildType;
use App\Form\PostType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminChildrenController extends AdminBaseController
{
    /**
     * @Route("/admin/children", name="admin_children")
     */
    public function index()
    {
        $child = $this->getDoctrine()->getRepository(Child::class)->findAll();
        $checkStock = $this->getDoctrine()->getRepository(Stock::class)->findAll();


        $forRender = parent::renderDefault();
        $forRender['title'] = 'Список детей';
        $forRender['h1'] = 'Список всех детей, нуждающихся в вещах (подарках)';
        $forRender['children'] = $child;
        $forRender['check_stock'] = $checkStock;
        return $this->render("admin/children/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/child/create", name="admin_child_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $child = new Child();
        $form = $this->createForm(ChildType::class, $child);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $child->setIsNotGifted();
            $em->persist($child);
            $em->flush();

            $this->addFlash('success', 'Запись ребенка добавлена');

            return $this->redirectToRoute('admin_children');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание записи ребенка';
        $forRender['form'] = $form->createView();
        return $this->render("admin/children/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/child/update/{id}", name="admin_child_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request)
    {
        $child = $this->getDoctrine()->getRepository(Child::class)->find($id);
        $form = $this->createForm(ChildType::class, $child);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $em->persist($child);
            $this->addFlash('success', 'Запись ребенка обновлена');
            $em->flush();

            return $this->redirectToRoute('admin_children');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание записи ребенка';
        $forRender['form'] = $form->createView();
        return $this->render("admin/children/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/child/delete/{id}", name="admin_child_delete")
     * @param int $id
     * @param Request $request
     */
    public function delete(int $id, Request $request)
    {
        $child = $this->getDoctrine()->getRepository(Child::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($child);
        $this->addFlash('success', 'Пост удален');
        $em->flush();

        return $this->redirectToRoute('admin_children');
    }
}