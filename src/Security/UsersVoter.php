<?php

namespace App\Security;

use App\Entity\Formation;
use App\Entity\Utilisateurs;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FormationVoter extends Voter
{
    public const ADD = 'user_add';
    public const EDIT = 'user_edit';
       public const DELETE = 'user_delete';

    private Security $security;
    private LoggerInterface $logger;

    public function __construct(Security $security, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true) 
            && $subject instanceof Formation;
    }

    protected function voteOnAttribute(string $attribute, mixed $formation, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Utilisateurs) {
            $this->logger->warning("Access denied: Anonymous user tried to {$attribute} a formation.");
            return false;
        }

        $this->logger->info("Checking {$attribute} permission for user {$user->getEmail()}");

        // ADMIN has full access
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->logger->info("Access granted: ROLE_ADMIN can {$attribute}.");
            return true;
        }

        // Check permissions based on attribute
        switch ($attribute) {
            case self::EDIT:
                $result = $this->canEdit($user);
                break;
            case self::DELETE:
                $result = $this->canDelete($user);
                break;
            default:
                $result = false;
        }

        if (!$result) {
            $this->logger->warning("Access denied: User {$user->getEmail()} does not have permission to {$attribute}.");
        }

        return $result;
    }

    private function canEdit(Utilisateurs $user): bool
    {
        // GESTIONNAIRE can edit
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            $this->logger->info("Access granted: ROLE_GESTIONNAIRE can edit.");
            return true;
        }
        return false;
    }

    private function canDelete(Utilisateurs $user): bool
    {
        // Only ADMIN can delete (already handled in main check)
        return false;
    }
}



















// namespace App\Security;

// use App\Entity\Formation;

// use App\Entity\Utilisateurs;
// use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Core\Authorization\Voter\Voter;

// class FormationVoter extends Voter
// {
//     // Define your attributes
//     public const EDIT = 'EDIT';
//     public const DELETE = 'DELETE';

//     protected function supports(string $attribute, $subject): bool
//     {
//         // Only vote on Formation objects and specific attributes
//         return $subject instanceof Formation && 
//                in_array($attribute, [self::EDIT, self::DELETE]);
//     }

//     protected function voteOnAttribute(string $attribute, $formation, TokenInterface $token): bool
//     {
//         $user = $token->getUser();

//         // If user is not logged in, deny access
//         if (!$user instanceof Utilisateurs) {
//             return false;
//         }

//         // Check permissions based on attribute
//         return match ($attribute) {
//             self::EDIT => $this->canEdit($user),
//             self::DELETE => $this->canDelete($user),
//             default => false
//         };
//     }

//     private function canEdit(Utilisateurs $user): bool
//     {
//         // Only ADMIN and GESTIONNAIRE can edit
//         return in_array($user->getRole(), ['ROLE_ADMIN', 'ROLE_GESTIONNAIRE']);
//     }

//     private function canDelete(Utilisateurs $user): bool
//     {
//         // Only ADMIN can delete
//         return $user->getRole() === 'ROLE_ADMIN';
//     }
// }