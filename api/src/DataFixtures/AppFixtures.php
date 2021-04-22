<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture
{
    private $container;
    private $manager;

    public function __construct(ContainerInterface $container = null, EntityManager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        // $this->loadLanguages();
        $this->loadCountries();
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
}
