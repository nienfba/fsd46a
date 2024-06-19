<?php

namespace App\EventSubscriber;

use App\Exception\IsVerifiedAuthenticationException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class TestSubscriber implements EventSubscriberInterface
{
    public function onCheckPassportEvent(CheckPassportEvent $event): void
    {

        $user = $event->getPassport()->getUser();
        if($user->isVerified() === false)
            throw new IsVerifiedAuthenticationException();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => 'onCheckPassportEvent',
        ];
    }
}
