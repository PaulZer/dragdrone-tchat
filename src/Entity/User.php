<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Tchat\TchatUserInterface;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, TchatUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $tchat_resource_id;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TchatMessage", mappedBy="userTo")
     */
    private $receivedMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TchatMessage", mappedBy="userFrom")
     */
    private $sendedMessages;

    public function __construct()
    {
        $this->receivedMessages = new ArrayCollection();
        $this->sendedMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getTchatResourceId(): ?int
    {
        return $this->tchat_resource_id;
    }

    public function setTchatResourceId(?int $tchat_resource_id): self
    {
        $this->tchat_resource_id = $tchat_resource_id;

        return $this;
    }

    /**
     * @return Collection|TchatMessage[]
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(TchatMessage $receivedMessage): self
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages[] = $receivedMessage;
            $receivedMessage->setUserTo($this);
        }

        return $this;
    }

    public function removeReceivedMessage(TchatMessage $receivedMessage): self
    {
        if ($this->receivedMessages->removeElement($receivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getUserTo() === $this) {
                $receivedMessage->setUserTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TchatMessage[]
     */
    public function getSendedMessages(): Collection
    {
        return $this->sendedMessages;
    }

    public function addSendedMessage(TchatMessage $sendedMessage): self
    {
        if (!$this->sendedMessages->contains($sendedMessage)) {
            $this->sendedMessages[] = $sendedMessage;
            $sendedMessage->setUserFrom($this);
        }

        return $this;
    }

    public function removeSendedMessage(TchatMessage $sendedMessage): self
    {
        if ($this->sendedMessages->removeElement($sendedMessage)) {
            // set the owning side to null (unless already changed)
            if ($sendedMessage->getUserFrom() === $this) {
                $sendedMessage->setUserFrom(null);
            }
        }

        return $this;
    }

    
}
