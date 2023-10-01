<?php

namespace App\Entity;

use App\Repository\FollowingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowingRepository::class)]
class Following
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'followings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'target_user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $target_user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTargetUser(): ?User
    {
        return $this->target_user;
    }

    public function setTargetUser(?User $target_user): self
    {
        $this->target_user = $target_user;

        return $this;
    }
}
