<?php

namespace App\Controller;

use App\Repository\LanguageRepository;
use App\Serializer\Schema\LanguageSchema;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class LanguageController extends AbstractController
{
    private $languageRepository;

    private $schema;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->schema = new LanguageSchema();
    }

    /**
     * @Route("/languages", methods={"GET"})
     */
    public function fetchLanguages()
    {
        $languages = $this->languageRepository->findAll();
        $languages = $this->get('serializer')->normalize($languages, 'json', $this->schema->fetchLanguages());

        return new JsonResponse([
            "success" => true,
            "data" => $languages
        ], 200);
    }
}
