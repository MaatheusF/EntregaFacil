<?php

/*
Company: CodeBeaver 2023
Description: Entity do Doctrine que controla o Get/Set dos dados da tabela de usuários (user_authentication)
Author: Matheus Francisco Favero
License: Licença MIT
*/

namespace App\Entity;

use App\Repository\UserAuthEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAuthEntityRepository::class)]
#[ORM\Table(name: 'user_authentication')]
class UserAuthEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\SequenceGenerator(sequenceName: 'sequence_user_authentication_user_id',allocationSize: 1,initialValue: 1)]
    #[ORM\Column(name: 'user_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'user_username',length: 255, nullable: true)]
    private ?string $Username = null;

    #[ORM\Column(name: 'user_email', length: 255, nullable: true)]
    private ?string $Email = null;

    #[ORM\Column(name: 'user_password',length: 255, nullable: false)]
    private ?string $Password = null;

    #[ORM\Column(name: 'user_name', length: 255, nullable: true)]
    private ?string $Name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(?string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }
}
