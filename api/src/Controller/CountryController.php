<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CountryTranslationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{
    private $countryTranslationRepository;
    private $languageRepository;

    public function __construct(CountryTranslationRepository $countryTranslationRepository, LanguageRepository $languageRepository)
    {
        $this->countryTranslationRepository = $countryTranslationRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @Route("/countries", methods={"GET"})
     */
    public function fetchCountries(Request $request)
    {
        $lang = $request->query->get("lang");
        $language = $this->languageRepository->findOneBy(['code' => $lang ?? "en"]);
        if (!$language) {
            $language = $this->languageRepository->findOneBy(['code' => "en"]);
        }

        $countries = $this->countryTranslationRepository->findBy(['language' => $language->getId()]);
        $countries = $this->get('serializer')->normalize($countries, 'json', ["attributes" => ["name"]]);

        return new JsonResponse([
            "success" => true,
            "data" => $countries
        ], 200);
    }
}
