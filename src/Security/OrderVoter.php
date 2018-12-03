<?php
namespace App\Security;

use App\Entity\Order;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class OrderVoter extends Voter
{
    const PAYMENT = 'payment';
    const SEND = 'send';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::PAYMENT, self::SEND))) {
            return false;
        }

        if (!$subject instanceof Order) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Order $order */
        $post = $subject;

        switch ($attribute) {
            case self::PAYMENT:
                return $this->canPay($post);
            case self::SEND:
                return $this->canSend($post);
        }
    }

    private function canPay(Order $order)
    {
        return !$order->getPayed();
    }

    private function canSend(Order $order)
    {
        return $order->getPayed();
    }
}