<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use App\Serializer\Schema\CountrySchema;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CountryTranslationRepository;
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

        $countryTranslation = $this->countryTranslationRepository->findOneBy(['language' => $language->getId(), "country" => $country->getId()]);
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

        $countryTranslation = $this->countryTranslationRepository->findOneBy(['language' => $language->getId(), "country" => $country->getId()]);
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

        $countryTranslation = $this->countryTranslationRepository->findOneBy(['language' => $language->getId(), "country" => $country->getId()]);
        $countryTranslation = $this->get('serializer')->normalize($countryTranslation, 'json', $this->schema->fetchCountries());

        return new JsonResponse([
            "success" => true,
            "data" => $countryTranslation
        ], 200);
    }

    /**
     * @Route("/countries/name/{name}", methods={"GET"})
     */
    public function fetchCountriesByName($name, Request $request)
    {
        $language = $this->checkLang($request);
        if (strlen($name) < 2) {
            return new JsonResponse([
                "success" => false,
                "message" => "The length of the name must be greater than 2"
            ], 400);
        }

        $countriesTranslation = $this->countryTranslationRepository->findByName($name, $language);
        $countriesTranslation = $this->get('serializer')->normalize($countriesTranslation, 'json', $this->schema->fetchCountries());

        return new JsonResponse([
            "success" => true,
            "data" => $countriesTranslation
        ], 200);
    }

    /**
     * @Route("/country/flag/{fileName}", methods={"GET"})
     */
    public function getCountryFlag($fileName)
    {
        try {
            $file = new File($this->getParameter("flag_dir") . "/" . $fileName);
            return $this->file($file);
        } catch (\Throwable $th) {
            return new JsonResponse([
                "success" => false,
                "message" => "Flag not found"
            ], 400);
        }
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
