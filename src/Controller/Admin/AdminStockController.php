<?php


namespace App\Controller\Admin;


use App\Entity\Stock;
use App\Form\StockType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class AdminStockController extends AdminBaseController
{
    /**
     * @Route("/admin/stocks", name="admin_stocks")
     * @return Response
     */
    public function index()
    {
        $stocks = $this->getDoctrine()->getRepository(Stock::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Акции';
        $forRender['h1'] = 'Список всех акций';
        $forRender['stocks'] = $stocks;
        return $this->render("admin/stock/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/stock/create", name="admin_stock_create")
     * @param Request $request
     * @return RedirectResponse|Response
     *
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);


        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $stock->setCreateAtValue();

            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "stock";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $stock->setImage($imageFileName);
            }

            $em->persist($stock);
            $em->flush();

            return $this->redirectToRoute("admin_stocks");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание новой акции';
        $forRender['form'] = $form->createView();
        return $this->render("admin/stock/form.html.twig", $forRender);
    }


    /**
     * @Route("/admin/stock/update/{id}", name="admin_stock_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request, FileUploader $fileUploader)
    {
        $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id);
        $form = $this->createForm(StockType::class, $stock);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);


        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $stock->setUpdateAtValue();

            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "stock";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $stock->setImage($imageFileName);
            }

            $this->addFlash('success', 'Акция обновлена');
            $em->flush();

            return $this->redirectToRoute('admin_stocks');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Обновление акции';
        $forRender['form'] = $form->createView();
        return $this->render("admin/stock/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/stock/delete/{id}", name="admin_stock_delete")
     * @param int $id
     * @param Request $request
     */
    public function delete(int $id, Request $request)
    {
        $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($stock);
        $this->addFlash('success', 'Акция удалена');
        $em->flush();

        return $this->redirectToRoute('admin_stocks');
    }
}