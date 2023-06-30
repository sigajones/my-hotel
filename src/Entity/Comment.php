<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?chambre $chambre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToOne(inversedBy: 'comment', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?commande $commande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_enregistrment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChambre(): ?chambre
    {
        return $this->chambre;
    }

    public function setChambre(?chambre $chambre): static
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCommande(): ?commande
    {
        return $this->commande;
    }

    public function setCommande(commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getDateEnregistrment(): ?\DateTimeInterface
    {
        return $this->date_enregistrment;
    }

    public function setDateEnregistrment(\DateTimeInterface $date_enregistrment): static
    {
        $this->date_enregistrment = $date_enregistrment;

        return $this;
    }

}
