<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $stockDirectory;
    private $categoryDirectory;
    private $postDirectory;

    public function __construct($targetDirectory, $stockDirectory, $categoryDirectory, $postDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->stockDirectory = $stockDirectory;
        $this->categoryDirectory = $categoryDirectory;
        $this->postDirectory = $postDirectory;
    }

    public function upload(UploadedFile $file, string $nameEntity)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

//        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
//        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $fileName = $originalFilename.'-'.md5(uniqid()).'.'.$file->guessExtension();

        try {
            if ($nameEntity == "stock")
                $file->move($this->getStockDirectory(), $fileName);
            elseif ($nameEntity == "category")
                $file->move($this->getCategoryDirectory(), $fileName);
            elseif ($nameEntity == "post")
                $file->move($this->getPostDirectory(), $fileName);
            else
                $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getStockDirectory()
    {
        return $this->stockDirectory;
    }

    public function getCategoryDirectory()
    {
        return $this->categoryDirectory;
    }

    public function getPostDirectory()
    {
        return $this->postDirectory;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}