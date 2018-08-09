<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as TicketAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="veuillez entrer votre nom de famille"
     * )
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="veuillez entrer votre prénom"
     * )
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *     message="veuillez choisir une date"
     * )
     * @Assert\Date()
     */
    private $birth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer"
     * )
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="veuillez choisir un pays"
     * )
     * @Assert\Country()
     */
    private $country;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(
     *     type="bool"
     * )
     */
    private $reduced;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Ticket
     */
    public function setlastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Ticket
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    /**
     * @param \DateTimeInterface $birth
     * @return Ticket
     */
    public function setBirth(\DateTimeInterface $birth): self
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     * @return Ticket
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     * @return Ticket
     */
    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Ticket
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getReduced(): ?bool
    {
        return $this->reduced;
    }

    /**
     * @param bool $reduced
     * @return Ticket
     */
    public function setReduced(bool $reduced): self
    {
        $this->reduced = $reduced;

        return $this;
    }
}
