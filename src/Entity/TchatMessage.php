<?php

namespace App\Entity;

use App\Tchat\TchatUserInterface;
use App\Repository\TchatMessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TchatMessageRepository::class)
 */
class TchatMessage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sendedMessages")
     */
    private $userFrom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="receivedMessages")
     */
    private $userTo;

    public function __construct(TchatUserInterface $userFrom, TchatUserInterface $userTo, string $content)
    {
        $this->userFrom = $userFrom;
        $this->userTo = $userTo;
        $this->date = new \DateTime();
        $this->setContent($content);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUserFrom():?User
    {
        return $this->userFrom;
    }

    public function setUserFrom(?User $userFrom): self
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    public function getUserTo(): ?User
    {
        return $this->userTo;
    }

    public function setUserTo(?User $userTo): self
    {
        $this->userTo = $userTo;

        return $this;
    }

    public function serializeJSON(){
        return json_encode([
            "userFrom" => $this->getUserFrom()->getId(),
            "userTo" => $this->getUserTo()->getId(),
            "date" => $this->getDate()->format('d-m-y h:i:s'),
            "content" => $this->getContent()
        ]);
    }
}
