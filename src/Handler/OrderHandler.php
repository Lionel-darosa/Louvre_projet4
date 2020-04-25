<?php

namespace App\Handler;

use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderHandler
 * @package App\Handler
 */
class OrderHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * OrderHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function handle(Request $request): bool
    {
        $this->form = $this->formFactory->create(OrderType::class);

        $this->form->handleRequest($request);

        if($this->form->isSubmitted() and $this->form->isValid()) {
            $this->entityManager->persist($this->form->getData());
            $this->entityManager->flush();
            return true;
        }

        return false;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->form->getData();
    }

    /**
     * @return FormView
     */
    public function createView(): FormView
    {
        return $this->form->createView();
    }
}