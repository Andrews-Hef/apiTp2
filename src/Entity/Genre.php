<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert; 
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 * @UniqueEntity( fields={"libelle"}, message="le genre {{ value }} existe deja" )
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listGenreSimple", "listGenreFull"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listGenreSimple", "listGenreFull"})
     * @Assert\Length(min=2,max=50,minMessage="le libelle doit contenir au moins {{ limit }} caratère", maxMessage="le libelle  doit contenir au plus {{ limit }} caratère")
     * 
     */
    private $libelle;

    /**
     * 
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="genre")
     * @Groups({"listGenreFull"})

     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setGenre($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getGenre() === $this) {
                $livre->setGenre(null);
            }
        }

        return $this;
    }

    
}
