<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploaded_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sport_type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $event_datetime;

    /**
     * @ORM\Column(type="integer")
     */
    private $people_needed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(\DateTimeInterface $uploaded_at): self
    {
        $this->uploaded_at = $uploaded_at;

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

    public function getSportType(): ?string
    {
        return $this->sport_type;
    }

    public function setSportType(string $sport_type): self
    {
        $this->sport_type = $sport_type;

        return $this;
    }

    public function getEventDatetime(): ?\DateTimeInterface
    {
        return $this->event_datetime;
    }

    public function setEventDatetime(\DateTimeInterface $event_datetime): self
    {
        $this->event_datetime = $event_datetime;

        return $this;
    }

    public function getPeopleNeeded(): ?int
    {
        return $this->people_needed;
    }

    public function setPeopleNeeded(int $people_needed): self
    {
        $this->people_needed = $people_needed;

        return $this;
    }
}
