<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Traits\BaseEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{

    use BaseEntityTrait;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['order-list'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 50)]
    #[Groups(['order-list'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['order-list'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order-list'])]
    private ?string $address = null;

    #[ORM\Column(length: 30)]
    #[Groups(['order-list'])]
    private ?string $country = null;

    #[ORM\Column(length: 20)]
    #[Groups(['order-list'])]
    private ?string $state = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'orders')]
    #[Groups(['order-list'])]
    private Collection $product;

    #[ORM\Column]
    #[Groups(['order-list'])]
    private ?float $totalPrice = null;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->product->removeElement($product);

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }
}
