<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $alpha2;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $alpha3;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $unCode;

    /**
     * @ORM\OneToMany(targetEntity=CountryTranslation::class, mappedBy="country", orphanRemoval=true)
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

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(string $alpha2): self
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(string $alpha3): self
    {
        $this->alpha3 = $alpha3;

        return $this;
    }

    public function getUnCode(): ?string
    {
        return $this->unCode;
    }

    public function setUnCode(string $unCode): self
    {
        $this->unCode = $unCode;

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
            $countryTranslation->setCountry($this);
        }

        return $this;
    }

    public function removeCountryTranslation(CountryTranslation $countryTranslation): self
    {
        if ($this->countryTranslations->removeElement($countryTranslation)) {
            // set the owning side to null (unless already changed)
            if ($countryTranslation->getCountry() === $this) {
                $countryTranslation->setCountry(null);
            }
        }

        return $this;
    }
}
