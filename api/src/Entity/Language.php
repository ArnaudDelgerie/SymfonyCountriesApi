<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=CountryTranslation::class, mappedBy="language", orphanRemoval=true)
     */
    private $countryTranslations;

    public function __construct()
    {
        $this->countryTranslations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|CountryTranslation[]
     */
    public function getCountryTranslations(): Collection
    {
        return $this->countryTranslations;
    }

    public function addCountryTranslation(CountryTranslation $countryTranslation): self
    {
        if (!$this->countryTranslations->contains($countryTranslation)) {
            $this->countryTranslations[] = $countryTranslation;
            $countryTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeCountryTranslation(CountryTranslation $countryTranslation): self
    {
        if ($this->countryTranslations->removeElement($countryTranslation)) {
            // set the owning side to null (unless already changed)
            if ($countryTranslation->getLanguage() === $this) {
                $countryTranslation->setLanguage(null);
            }
        }

        return $this;
    }
}
