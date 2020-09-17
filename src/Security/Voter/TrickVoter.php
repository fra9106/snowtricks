<?php

namespace App\Security\Voter;

use App\Entity\Trick;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class TrickVoter
 * @package App\Security\Voter
 */
class TrickVoter extends Voter
{
    public const EDIT = 'edit';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject)
    {
        if (!$subject instanceof Trick) {
            return false;
        }

        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Trick $subject
     * @param TokenInterface $token
     * @return bool|void
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        switch ($attribute) {
            case self::EDIT:
                return $user === $subject->getUser();
                break;
        }
    }
}
