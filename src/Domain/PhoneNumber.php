<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Class PhoneNumber
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class PhoneNumber
{
    /** @var string */
    const PHONE_NUMBER_REGEX = '/[+0-9]+/';

    /** @var string */
    private $phoneNumber;

    /**
     * PhoneNumber constructor.
     *
     * @param string $phoneNumber Plain phone number parameter.
     *
     * @throws InvalidPhoneNumber
     */
    public function __construct(string $phoneNumber)
    {
        $this->setPhoneNumber($phoneNumber);
    }

    /**
     * @param string $phoneNumber Phone number.
     *
     * @return PhoneNumber
     *
     * @throws InvalidPhoneNumber
     */
    private function setPhoneNumber(string $phoneNumber): self
    {
        if (!preg_match(self::PHONE_NUMBER_REGEX, $phoneNumber)) {
            throw new InvalidPhoneNumber("Invalid phone number format for: {$phoneNumber}");
        }

        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }
}