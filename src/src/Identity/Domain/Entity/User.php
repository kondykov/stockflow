<?php

namespace StockFlow\Identity\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use LogicException;
use StockFlow\Identity\Domain\Entity\RBAC\Role;
use StockFlow\Shared\Identity\Domain\Enum\UserType;
use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimeStamps;

    public ?int $id = null;
    public string $email;
    public string $name = '';
    public string $password;
    public UserType $type {
        get => match (static::class) {
            Admin::class => UserType::Admin,
            Manager::class => UserType::Manager,
            default => throw new LogicException('Неизвестный тип пользователя: ' . static::class),
        };
    }

    /** @var Collection<int, Role> */
    public Collection $userRoles;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        foreach ($this->userRoles as $role) {
            $roles[] = 'ROLE_' . strtoupper($role->name);
        }

        return array_unique($roles);
    }

    /**
     * @param Collection<int, Role> $userRoles
     */
    public function setUserRoles(Collection $userRoles): static
    {
        $this->userRoles = $userRoles;

        return $this;
    }

    public function addRole(Role $role): static
    {
        if (!$this->userRoles->contains($role)) {
            $this->userRoles->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        if ($this->userRoles->contains($role)) {
            $this->userRoles->removeElement($role);
        }
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return false;
    }
}
