<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\VerificationId;
use Doctrine\DBAL\Types\GuidType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\UuidIdEntity;

class VerificationIdType extends GuidType
{
    const VERIFICATION_ID = 'VerificationId';

    public function getName()
    {
        return static::VERIFICATION_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return VerificationId::class;
    }
}