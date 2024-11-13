<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;

class UserChecker implements UserCheckerInterface
{
    // Cette méthode est appelée avant que l'utilisateur ne soit authentifié
    public function checkPreAuth(UserInterface $user): void
    {
        // Exemple : Vérifiez si l'utilisateur est actif avant l'authentification
        if (!$user->isActive()) {
            throw new DisabledException('Your account is disabled.');
        }

        // Exemple : Vérifiez si l'utilisateur est verrouillé avant l'authentification
        if ($user->isLocked()) {
            throw new LockedException('Your account is locked.');
        }

        // Ajoutez d'autres règles ou contrôles si nécessaire
    }

    // Cette méthode est appelée après l'authentification de l'utilisateur
    public function checkPostAuth(UserInterface $user): void
    {
        // Exemple : Après l'authentification, vous pouvez vérifier d'autres critères, par exemple :
        // Vérifiez si l'utilisateur a les bonnes permissions, etc.

        if ($user->isSuspended()) {
            throw new AccountStatusException('Your account is suspended.');
        }
    }
}
