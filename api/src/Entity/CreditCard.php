<?php

namespace App\Entity;

use App\Repository\CreditCardRepository;
use Inacho\CreditCard as CreditCardValidator;
use InvalidArgumentException;


class CreditCard
{

    private int $id;

    private string $holderName;

    private string $cardNumber;

    private string $expiryDate;

    private User $user;

    /**
     * @param string $holderName
     * @param string $cardNumber
     * @param string $expiryDate
     * @param User $user
     */


    public function __construct(string $holderName, string $cardNumber, string $expiryDate, User $user)
    {
        $this->holderName = $holderName;
        $this->cardNumber = $cardNumber;
        $this->expiryDate = $expiryDate;
        $this->user = $user;
    }

    public function isExpired(): bool
    {
        $expiryDate = \DateTime::createFromFormat('m/y', $this->expiryDate);
        $currentDate = new \DateTime();

        return $expiryDate < $currentDate;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        //TODO set the owning side of ex: addCreditCard

    }

    public function getHolderName(): string
    {
        return $this->holderName;
    }

    public function setHolderName(string $holderName): void
    {
        $this->holderName = $holderName;
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): void
    {
        $result = CreditCardValidator::validCreditCard($cardNumber);

        if ($result['valid']) {
            $this->cardNumber = $cardNumber;
        } else {
            throw new InvalidArgumentException('The number of credit card is incorrect');
        }
    }

    public function getExpiryDate(): string
    {
        if ($this->isExpired()) {
            throw new \InvalidArgumentException('The credit card has expired.');
        }else{
            return $this->expiryDate;
        }
    }

    public function setExpiryDate(string $expiryDate): void
    {
        $this->expiryDate = $expiryDate;
        if ($this->isExpired()) {
            throw new \InvalidArgumentException('The credit card has expired.');
        }
    }

    public function getUser(): User
    {
        return $this->user;
    }







}
