<?php declare(strict_types=1);

namespace App\Domain;

interface DateManager
{
    public function createFromNow(): \DateTimeImmutable;

    public function createInMinutes(int $minutes): \DateTimeImmutable;
}
