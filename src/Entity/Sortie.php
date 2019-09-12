<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SortieRepository")
 */
class Sortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

   

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sortie_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

 



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Streetname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Streetnum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Cp;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Ville;

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getSortieName(): ?string
    {
        return $this->sortie_name;
    }

    public function setSortieName(string $sortie_name): self
    {
        $this->sortie_name = $sortie_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStreetname(): ?string
    {
        return $this->Streetname;
    }

    public function setStreetname(string $Streetname): self
    {
        $this->Streetname = $Streetname;

        return $this;
    }

    public function getStreetnum(): ?string
    {
        return $this->Streetnum;
    }

    public function setStreetnum(string $Streetnum): self
    {
        $this->Streetnum = $Streetnum;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->Cp;
    }

    public function setCp(string $Cp): self
    {
        $this->Cp = $Cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }
}
