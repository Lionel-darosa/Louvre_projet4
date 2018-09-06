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
 * @ORM\Table(name="odr")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="order", orphanRemoval=true, cascade={"persist"})
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

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Order
     */
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

    /**
     * @param Ticket $ticket
     * @return Order
     */
    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setOrder($this);
        }

        return $this;
    }

    /**
     * @param Ticket $ticket
     * @return Order
     */
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

    /**
     * @return \DateTimeInterface|null
     */
    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    /**
     * @param \DateTimeInterface $orderDate
     * @return Order
     */
    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getChoiceDate(): ?\DateTimeInterface
    {
        return $this->choiceDate;
    }

    /**
     * @param \DateTimeInterface $choiceDate
     * @return Order
     */
    public function setChoiceDate(\DateTimeInterface $choiceDate): self
    {
        $this->choiceDate = $choiceDate;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getHalf(): ?bool
    {
        return $this->half;
    }

    /**
     * @param bool $half
     * @return Order
     */
    public function setHalf(bool $half): self
    {
        $this->half = $half;

        return $this;
    }

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     * @throws \Exception
     * @assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload){

        $today = new \DateTime();
        $easterDate = new \DateTime(date('d-m-Y', easter_date($this->getChoiceDate()->format('Y'))));

        $holidays = array(
            '01/01',
            '01/05',
            '08/05',
            '14/07',
            '15/08',
            '01/11',
            '11/11',
            '25/12',
            $easterDate->add(new \DateInterval('P1D'))->format('d/m'),
            $easterDate->add(new \DateInterval('P38D'))->format('d/m'),
            $easterDate->add(new \DateInterval('P11D'))->format('d/m')
        );

        if (in_array($this->getChoiceDate()->format('d/m'), $holidays)){
            $context->buildViolation('Vous ne pouvez pas réserver pour un jour férié')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if ($this->getChoiceDate()->format('N') === '7'){
            $context->buildViolation('Vous ne pouvez pas réserver le Dimanche')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if ($this->getChoiceDate()->format('N') === '2'){
            $context->buildViolation('Le musée est fermé le Mardi')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if ( $this->getChoiceDate()->format('d/m/Y') === $today->format('d/m/Y') and $today->format('G') > '13' and $this->getHalf() === false){
            $context->buildViolation('Vous ne pouvez pas prendre de billet journée après 14 heures')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if ($this->getChoiceDate()->format('d/m/Y') < $today->format('d/m/Y')){
            $context->buildViolation('vous ne pouvez pas réserver pour un jour passé')
                ->atPath('choiceDate')
                ->addViolation();
        }
        if ( $this->getChoiceDate()->format('d/m/Y') === $today->format('d/m/Y') and $today->format('G') > '17'){
            $context->buildViolation('Vous ne pouvez plus prendre de billet, le musée est fermé')
                ->atPath('choiceDate')
                ->addViolation();
        }

    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePercist(){
        $this->orderDate= new \DateTime();
    }
}

