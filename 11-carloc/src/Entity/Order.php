<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[ORM\ManyToMany(targetEntity: Vehicle::class, inversedBy: 'orders')]
    private Collection $idVehicle;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTimeDeparture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTimeEnd = null;

    #[ORM\Column]
    private ?int $totalPrice = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->idVehicle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getIdVehicle(): Collection
    {
        return $this->idVehicle;
    }

    public function addIdVehicle(Vehicle $idVehicle): static
    {
        if (!$this->idVehicle->contains($idVehicle)) {
            $this->idVehicle->add($idVehicle);
        }

        return $this;
    }

    public function removeIdVehicle(Vehicle $idVehicle): static
    {
        $this->idVehicle->removeElement($idVehicle);

        return $this;
    }

    public function getDateTimeDeparture(): ?\DateTimeInterface
    {
        return $this->dateTimeDeparture;
    }

    public function setDateTimeDeparture(\DateTimeInterface $dateTimeDeparture): static
    {
        $this->dateTimeDeparture = $dateTimeDeparture;

        return $this;
    }

    public function getDateTimeEnd(): ?\DateTimeInterface
    {
        return $this->dateTimeEnd;
    }

    public function setDateTimeEnd(\DateTimeInterface $dateTimeEnd): static
    {
        $this->dateTimeEnd = $dateTimeEnd;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function calculateTotalDailyPrice(): int
    {
        $totalDailyPrice = 0;

        /** @var Vehicle $vehicle */
        foreach ($this->getIdVehicle() as $vehicle) {
            $totalDailyPrice += $vehicle->getDailyPrice();
        }

        return $totalDailyPrice;
    }

    public function calculateTotalDays(): int
    {
        $startDate = $this->getDateTimeDeparture();
        $endDate = $this->getDateTimeEnd();

        $totalDays = 1; // Défaut : 1 jour
        if ($startDate < $endDate) {
            $totalDays = $startDate->diff($endDate)->days; // +1 pour inclure la date de fin
            if($totalDays == 0) $totalDays = 1; //
        }

        return $totalDays;
    }
}
