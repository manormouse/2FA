<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Verification;
use App\Domain\VerificationDoesNotExists;
use App\Domain\VerificationId;
use App\Domain\VerificationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\UuidGenerator;

class VerificationRepository implements VerificationRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist(Verification $verification): Verification
    {
        $this->entityManager->persist($verification);
        $this->entityManager->flush();

        return $verification;
    }

    public function ofId(VerificationId $verificationId):? Verification
    {
        return $this->entityManager->find(Verification::class, $verificationId);
    }

    /**
     * @param VerificationId $verificationId
     * @return Verification
     * @throws VerificationDoesNotExists
     */
    public function ofIdOrFail(VerificationId $verificationId): Verification
    {
        $verification = $this->ofId($verificationId);

        if ($verification === null) {
            throw new VerificationDoesNotExists("Verification {$verificationId->id()} does not exists");
        }

        return $verification;
    }

    public function generateId(): VerificationId
    {
        return new VerificationId((new UuidGenerator())->generate($this->entityManager, Verification::class));
    }
}