<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\CountryTranslation;
use App\Entity\Language;
use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture
{
    private $container;
    private $manager;
    private $languageRepository;
    private $countryRepository;

    public function __construct(
        ContainerInterface $container = null,
        EntityManager $manager,
        LanguageRepository $languageRepository,
        CountryRepository $countryRepository
    ) {
        $this->container = $container;
        $this->manager = $manager;
        $this->languageRepository = $languageRepository;
        $this->countryRepository = $countryRepository;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadLanguages();
        $this->loadCountries();
        $this->loadCountriesTranslations("/CountriesEn.json", "en");
        $this->loadCountriesTranslations("/CountriesFr.json", "fr");
        $this->loadCountriesTranslations("/CountriesEs.json", "es");
        $this->loadCountriesTranslations("/CountriesDe.json", "de");
        $this->loadCountriesTranslations("/CountriesIt.json", "it");
        $this->loadCountriesTranslations("/CountriesPt.json", "Pt");
        $this->loadCountriesTranslations("/CountriesRu.json", "Ru");
    }

    private function loadLanguages()
    {
        $languages = json_decode(file_get_contents($this->container->getParameter("json_dir") . "/Language.json"), true);

        foreach ($languages as $data) {
            $language = new Language();
            $language->setName($data['name']);
            $language->setCode($data['code']);
            $this->manager->persist($language);
        }

        $this->manager->flush();
    }

    private function loadCountries()
    {
        $countries = json_decode(file_get_contents($this->container->getParameter("json_dir") . "/CountriesEn.json"), true);

        foreach ($countries as $data) {
            $country = new Country();
            $country->setAlpha2($data['alpha2']);
            $country->setAlpha3($data['alpha3']);
            $country->setUnCode($data['id']);
            $this->manager->persist($country);
        }

        $this->manager->flush();
    }

    private function loadCountriesTranslations($file, $language)
    {
        $translations = json_decode(file_get_contents($this->container->getParameter("json_dir") . $file), true);
        $language = $this->languageRepository->findOneBy(['code' => $language]);

        foreach ($translations as $data) {
            $country = $this->countryRepository->findOneBy(['alpha2' => $data['alpha2']]);
            $translation = new CountryTranslation();
            $translation->setName($data['name']);
            $translation->setLanguage($language);
            $translation->setCountry($country);
            $this->manager->persist($translation);
        }

        $this->manager->flush();
    }
}
