<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ActivityConstraint extends Constraint
{
    public string $leadOrAccountMessage = 'Une activité doit être liée à un lead OU un compte.';
    public string $opportunityNeedsAccountMessage = 'Une opportunité nécessite un compte associé.';
    public string $opportunityAccountMissmatchMessage = "L’opportunité ne correspond pas au compte.";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
