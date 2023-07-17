<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User implements UserInterface
{
    private string $id;
    private string $name;
    private string $surname;
    private string $email;
    private string $password;
    private string $username;
    private ?string $token;
    private ?string $avatar;
    private ?string $resetPasswordToken;
    private bool $active;
    private \DateTime $updatedAt;
    private \DateTime $createdAt;

   /**
    * @var Collection|CreditCard[]
    */
    private Collection $creditCards;


    /**
     * @param string $email
     * @param string $password
     * @param string $username
     */
    public function __construct(string $email, string $password, string $username, string $name, string $surname)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->setEmail($email);
        $this->name = $name;
        $this->surname = $surname;
        $this->password = $password;
        $this->username = $username;
        $this->avatar = null;
        $this->token = \sha1(uniqid());
        $this->resetPasswordToken = null;
        $this->active = false;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
        $this->creditCards = new ArrayCollection();
    }


    public function removeCreditCard(CreditCard $creditCard): self
    {
        if (($key = array_search($creditCard, $this->creditCards, true)) !== false) {
            unset($this->creditCards[$key]);
        }

        return $this;
    }

    public function addCreditCard(CreditCard $creditCard): self
    {
        if (!in_array($creditCard, $this->creditCards, true)) {
            $this->creditCards[] = $creditCard;
        }

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        if (!\filter_var($email, \FILTER_VALIDATE_EMAIL)){
            throw new \LogicException('Invalid email');
        }else{
            $this->email = $email;
        }

    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return CreditCard[]
     */
    public function getCreditCards(): array
    {
        return $this->creditCards;
    }

    public function getSalt(): string
    {
        return "hola";
    }
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return $this->email;
    }

    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        return [];
    }
}