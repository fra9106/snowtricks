<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileUploader 
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $brouette): string
    {  
        $load = $this->targetDirectory.'/'.$brouette;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($load,$newFilename);

        return $newFilename;
    }
}
