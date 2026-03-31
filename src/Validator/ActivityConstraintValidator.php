<?php

namespace App\Validator;

use App\Entity\Activity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ActivityConstraintValidator extends ConstraintValidator
{
    public function validate($activity, Constraint $constraint): void
    {
        /** @var ActivityConstraint $constraint */

        if (!$activity instanceof Activity) {
            return;
        }

        // Lead et Account ne peuvent pas être renseignés dans une même activité
        if (($activity->getLead() && $activity->getAccount()) ||
            (!$activity->getLead() && !$activity->getAccount())
        ) {

            $this->context->buildViolation($constraint->leadOrAccountMessage)
                ->atPath('lead')
                ->addViolation();
        }

        // Un compte doit être renseigné si une activité concerne une opportunité
        if (($activity->getOpportunity() && !$activity->getAccount())) {
            $this->context->buildViolation($constraint->opportunityNeedsAccountMessage)
                ->atPath('opportunity')
                ->addViolation();
        }

        // Le compte renseigné doit être le même que celui de l'opportunité
        if (
            $activity->getOpportunity() &&
            $activity->getOpportunity()->getAccount() !== $activity->getAccount()
        ) {

            $this->context->buildViolation($constraint->opportunityAccountMissmatchMessage)
                ->atPath('opportunity')
                ->addViolation();
        }
    }
}
