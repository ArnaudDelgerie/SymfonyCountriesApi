<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CountryTranslationRepository;
use App\Serializer\Schema\CountrySchema;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{
    private $countryRepository;
    private $countryTranslationRepository;
    private $languageRepository;
    private $schema;

    public function __construct(
        CountryRepository $countryRepository,
        CountryTranslationRepository $countryTranslationRepository,
        LanguageRepository $languageRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->countryTranslationRepository = $countryTranslationRepository;
        $this->languageRepository = $languageRepository;
        $this->schema = new CountrySchema();
    }

    /**
     * @Route("/countries", methods={"GET"})
     */
    public function fetchCountries(Request $request)
    {
        $language = $this->checkLang($request);

        $countriesTranslation = $this->countryTranslationRepository->findBy(['language' => $language->getId()]);
        $countriesTranslation = $this->get('serializer')->normalize($countriesTranslation, 'json', $this->schema->fetchCountries());

        return new JsonResponse([
            "success" => true,
            "data" => $countriesTranslation
        ], 200);
    }

    /**
     * @Route("/country/alpha2/{code}", methods={"GET"})
     */
    public function fetchCountryByAlpha2($code, Request $request)
    {
        $language = $this->checkLang($request);
        $country = $this->countryRepository->findOneBy(['alpha2' => $code]);
        if (!$country) {
            return new JsonResponse([
                "success" => false,
                "message" => "Country not found" 
            ], 400);
        }

        $countryTranslation = $this->countryTranslationRepository->findBy(['language' => $language->getId(), "country" => $country->getId()]);
        $countryTranslation = $this->get('serializer')->normalize($countryTranslation, 'json', $this->schema->fetchCountries());

        return new JsonResponse([
            "success" => true,
            "data" => $countryTranslation
        ], 200);
    }

    /**
     * @Route("/country/alpha3/{code}", methods={"GET"})
     */
    public function fetchCountryByAlpha3($code, Request $request)
    {
        $language = $this->checkLang($request);
        $country = $this->countryRepository->findOneBy(['alpha3' => $code]);
        if (!$country) {
            return new JsonResponse([
                "success" => false,
                "message" => "Country not found" 
            ], 400);
        }

        $countryTranslation = $this->countryTranslationRepository->findBy(['language' => $language->getId(), "country" => $country->getId()]);
        $countryTranslation = $this->get('serializer')->normalize($countryTranslation, 'json', $this->schema->fetchCountries());

        return new JsonResponse([
            "success" => true,
            "data" => $countryTranslation
        ], 200);
    }

    /**
     * @Route("/country/uncode/{code}", methods={"GET"})
     */
    public function fetchCountryByUncode($code, Request $request)
    {
        $language = $this->checkLang($request);
        $country = $this->countryRepository->findOneBy(['unCode' => $code]);
        if (!$country) {
            return new JsonResponse([
                "success" => false,
                "message" => "Country not found" 
            ], 400);
        }

        $countryTranslation = $this->countryTranslationRepository->findBy(['language' => $language->getId(), "country" => $country->getId()]);
        $countryTranslation = $this->get('serializer')->normalize($countryTranslation, 'json', $this->schema->fetchCountries());

        return new JsonResponse([
            "success" => true,
            "data" => $countryTranslation
        ], 200);
    }

    private function checkLang($request)
    {
        $lang = $request->query->get("lang");
        $language = $this->languageRepository->findOneBy(['code' => $lang ?? "en"]);
        if (!$language) {
            $language = $this->languageRepository->findOneBy(['code' => "en"]);
        }

        return $language;
    }
}
