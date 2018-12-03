<?php

namespace App\Tests\Validator\Constraints;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use App\Validator\Constraints\MoreThanThousand;
use App\Validator\Constraints\MoreThanThousandValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * Class MoreThanThousandValidatorTest
 * @package App\Tests\Validator\Constraints
 */
class MoreThanThousandValidatorTest extends TestCase
{
    /**
     * @dataProvider countTickets
     * @param int $countTickets
     */
    public function testValidate(int $countTickets)
    {
        $ticketRepository = $this->createMock(TicketRepository::class);

        $ticketRepository
            ->method("countTicketsByVisitDate")
            ->willReturn($countTickets)
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager
            ->method("getRepository")
            ->willReturn($ticketRepository)
        ;

        $order = $this->createMock(Order::class);

        $order
            ->method("getTickets")
            ->willReturn(new ArrayCollection([
                $this->createMock(Ticket::class),
                $this->createMock(Ticket::class)
            ]))
        ;

        $order
            ->method('getChoiceDate')
            ->willReturn(new \DateTime())
        ;

        $context = $this->createMock(ExecutionContextInterface::class);

        $constraintViolationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $context
            ->method("buildViolation")
            ->willReturn($constraintViolationBuilder)
        ;

        $constraint = $this->createMock(MoreThanThousand::class);

        $validator = new MoreThanThousandValidator($entityManager);

        $validator->initialize($context);

        $this->assertNull($validator->validate($order, $constraint));
    }

    /**
     * @return array
     */
    public function countTickets(): array
    {
        return [
            [
                "countTickets" => 1000
            ],
            [
                "countTickets" => 999
            ]
        ];
    }
}