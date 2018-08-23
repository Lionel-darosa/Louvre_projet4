<?php
namespace App\Validator\Constraints;

use App\Entity\Order;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class MoreThanThousandValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MoreThanThousand constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function validate($value, Constraint $constraint)
    {
        $dailyTickets = $this->entityManager->getRepository(Ticket::class)->countTicketsByVisitDate($value->getChoiceDate());
        $orderTickets = count($value->getTickets());

        if ($dailyTickets > 999){
            $this->context->buildViolation('Tout les billets pour cette date ont été vendus')
                ->addViolation();
        }
        if ($dailyTickets < 1000 and ($orderTickets + $dailyTickets) > 1000 ){
            $this->context->buildViolation("Il ne reste que ". (1000-$dailyTickets) ." billets, vous devez retirer ". ($orderTickets-(1000-$dailyTickets)). " billets de votre commande")
                ->addViolation();
        }
    }
}