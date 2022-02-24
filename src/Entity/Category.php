<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Shelf::class)]
    private $shelf;

    public function __construct()
    {
        $this->shelf = new ArrayCollection();
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

    /**
     * @return Collection<int, Shelf>
     */
    public function getShelf(): Collection
    {
        return $this->shelf;
    }

    public function addShelf(Shelf $shelf): self
    {
        if (!$this->shelf->contains($shelf)) {
            $this->shelf[] = $shelf;
            $shelf->setCategory($this);
        }

        return $this;
    }

    public function removeShelf(Shelf $shelf): self
    {
        if ($this->shelf->removeElement($shelf)) {
            // set the owning side to null (unless already changed)
            if ($shelf->getCategory() === $this) {
                $shelf->setCategory(null);
            }
        }

        return $this;
    }

}
