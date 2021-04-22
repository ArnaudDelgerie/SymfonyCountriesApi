<?php

namespace App\DataFixtures;

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
        $this->loadLanguages();
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
}
