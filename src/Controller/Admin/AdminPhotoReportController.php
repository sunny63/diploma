<?php


namespace App\Controller\Admin;

use App\Entity\Child;
use App\Entity\PhotoReport;
use App\Entity\Stock;
use App\Form\ChildType;
use App\Form\PhotoReportType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPhotoReportController extends AdminBaseController
{
    /**
     * @Route("/admin/photoReports", name="admin_photo_reports")
     */
    public function index()
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->findAll();
        $checkStock = $this->getDoctrine()->getRepository(Stock::class)->findAll();


        $forRender = parent::renderDefault();
        $forRender['title'] = 'Фотоотчеты';
        $forRender['h1'] = 'Список всех фотоотчетов';
        $forRender['photo_reports'] = $photoReport;
        $forRender['check_stock'] = $checkStock;
        $forRender['id_stock'] = 0;
        return $this->render("admin/photoReport/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photoReport/create/{id_stock}", name="admin_photo_report_create", defaults = {"id_stock" = 0})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request, FileUploader $fileUploader, int $id_stock)
    {
        $photoReport = new PhotoReport();
        $photoReport->setIsDraft();

        if ($id_stock)
        {
            $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id_stock);
            $photoReport->setStock($stock);
            $form = $this->createForm(PhotoReportType::class, $photoReport, array('is_stock_photo_report' => true));
        }
        else
            $form = $this->createForm(PhotoReportType::class, $photoReport);


        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $photoReport->setCreateAtValue();

            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "photoReport";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $photoReport->setImage($imageFileName);
            }

            $em->persist($photoReport);
            $em->flush();

            $this->addFlash('success', 'Фотоотчет создан');

            if ($id_stock)
                return $this->redirectToRoute('admin_stock_photo_reports', array('id' => $id_stock));
            else
                return $this->redirectToRoute('admin_photo_reports');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание фотоотчета';
        $forRender['form'] = $form->createView();
        return $this->render("admin/photoReport/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photoReport/doPublished/{id},{id_stock}", name="admin_photo_report_do_published", defaults = {"id_stock" = 0})
     * @param int $id
     * @param Request $request
     */
    public function doPublished(int $id, Request $request, int $id_stock)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $photoReport->setIsPublished();

        $this->addFlash('success', 'Фотоотчет опубликован!');
        $em->flush();

        if ($id_stock)
            return $this->redirectToRoute('admin_stock_photo_reports', array('id' => $id_stock));
        else
            return $this->redirectToRoute('admin_photo_reports');
    }


    /**
     * @Route("/admin/photoReport/doDraft/{id},{id_stock}", name="admin_photo_report_do_draft", defaults = {"id_stock" = 0})
     * @param int $id
     * @param Request $request
     */
    public function doDraft(int $id, Request $request, int $id_stock)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $photoReport->setIsDraft();

        $this->addFlash('success', 'Фотоотчет скрыт!');
        $em->flush();

        if ($id_stock)
            return $this->redirectToRoute('admin_stock_photo_reports', array('id' => $id_stock));
        else
            return $this->redirectToRoute('admin_photo_reports');
    }

    /**
     * @Route("/admin/photoReport/update/{id},{id_stock}", name="admin_photo_report_update", defaults = {"id_stock" = 0})
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request, FileUploader $fileUploader, int $id_stock)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $form = $this->createForm(PhotoReportType::class, $photoReport);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "photoReport";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $photoReport->setImage($imageFileName);
            }

            $em->persist($photoReport);
            $this->addFlash('success', 'Фотоотчет обновлен');
            $em->flush();

            if ($id_stock)
                return $this->redirectToRoute('admin_stock_photo_reports', array('id' => $id_stock));
            else
                return $this->redirectToRoute('admin_photo_reports');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Обновление фотоотчета';
        $forRender['form'] = $form->createView();
        return $this->render("admin/photoReport/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photoReport/delete/{id},{id_stock}", name="admin_photo_report_delete", defaults = {"id_stock" = 0})
     * @param int $id
     * @param Request $request
     */
    public function delete(int $id, Request $request, int $id_stock)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($photoReport);
        $this->addFlash('success', 'Фотоотчет удален');
        $em->flush();

        if ($id_stock)
            return $this->redirectToRoute('admin_stock_photo_reports', array('id' => $id_stock));
        else
            return $this->redirectToRoute('admin_photo_reports');
    }

    /**
     * @Route("/admin/photoReport/photos/{id}", name="admin_photo_report_photos")
     * @param int $id
     * @param Request $request
     */
    public function showPhotos(int $id, Request $request)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $photos = $photoReport->getPhotos();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Список фотографий';
        $forRender['h1'] = 'Все фотографии для данного фотоотчета';
        $forRender['photos'] = $photos;
        $forRender['id_photo_report'] = $id;
        return $this->render("admin/photoReport/photo/index.html.twig", $forRender);
    }
}