<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as OrderAssert;
use Symfony\Component\Validator\Context\ExecutionContextFactoryInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @OrderAssert\MoreThanThousand
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide."
     * )
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="order", orphanRemoval=true)
     */
    private $tickets;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *     message = "veuillez choisir une date"
     * )
     * @Assert\Date()
     *
     */
    private $choiceDate;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(
     *     type="bool"
     * )
     */
    private $half;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setOrder($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getOrder() === $this) {
                $ticket->setOrder(null);
            }
        }

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getChoiceDate(): ?\DateTimeInterface
    {
        return $this->choiceDate;
    }

    public function setChoiceDate(\DateTimeInterface $choiceDate): self
    {
        $this->choiceDate = $choiceDate;

        return $this;
    }

    public function getHalf(): ?bool
    {
        return $this->half;
    }

    public function setHalf(bool $half): self
    {
        $this->half = $half;

        return $this;
    }

    public function validate(ExecutionContextInterface $context, $payload){

        $today = $this->getOrderDate();
        $easterDate = new \DateTime(date('d-m-Y', easter_date($this->getChoiceDate())));

        $holidays = array(
            '01/01',
            '01/05',
            '08/05',
            '14/07',
            '15/08',
            '01/11',
            '11/11',
            '25/12',
            $easterDate->add(new \DateInterval('P2D'))->format('d/m'),
            $easterDate->add(new \DateInterval('P38D'))->format('d/m'),
            $easterDate->add(new \DateInterval('P11D'))->format('d/m')
        );

        if (in_array($this->getChoiceDate()->format('d/m'), $holidays)){
            $context->buildViolation('Pas possible de réserver pour un jour férié')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if ($this->getChoiceDate()->format('N') === '0'){
            $context->buildViolation('Pas possible de réserver pour le Dimanche')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if ($this->getChoiceDate()->format('N') === '2'){
            $context->buildViolation('Le musée est fermé le Mardi')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if (date('d/m/Y', $this->getChoiceDate()) === date('d/m/Y', $today) and date('G', $today) > '13' and $this->getHalf() == 'false'){
            $context->buildViolation('Pas possible de prendre un billet journée après 14 heures')
                ->atPath('choiceDate')
                ->addViolation();
        }

    }
}

