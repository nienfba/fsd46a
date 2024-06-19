<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class IsVerifiedAuthenticationException extends AuthenticationException
{

    /**
     * Message key to be used by the translation component.
     */
    public function getMessageKey(): string
    {
        return 'Please validate your email before login.';
    }

}