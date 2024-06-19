<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Exception\IsVerifiedAuthenticationException;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

use Symfony\Component\Security\Http\Authenticator\Passport\Passport;


class SecuritySubscriber
{

    public static function getSubscribedEvents(): array
    {
        // return the subscribed events, their methods and priorities
        return [
            CheckPassportEvent::class => ['onCheckPassport',-10],
            LoginFailureEvent::class => 'onLoginFailure'
        ];
    }


    public function onCheckPassport(CheckPassportEvent $event)
    {

        $passport = $event->getPassport();
        // dd(get_class($passport));
        if (!$passport instanceof Passport) {
            throw new \Exception('Unexpected passport type');
        }

        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

        if (!$user->isVerified()) {
            throw new IsVerifiedAuthenticationException();
        }
    }
}