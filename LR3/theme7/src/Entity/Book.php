<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

// Сущность книги
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // Название
    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    // Автор
    #[ORM\Column(type: 'string', length: 100)]
    private $author;

    // URL обложки
    #[ORM\Column(type: 'string', length: 64)]
    private $coverUrl;

    // URL файла с книгой
    #[ORM\Column(type: 'string', length: 64)]
    private $fileUrl;

    // Дата последнего прочтения
    #[ORM\Column(type: 'datetime')]
    private $dateRead;

    // Дальше - стандартные геттеры и сеттеры

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->coverUrl;
    }

    public function setCoverUrl(string $coverUrl): self
    {
        $this->coverUrl = $coverUrl;

        return $this;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function setFileUrl(string $fileUrl): self
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    public function getDateRead(): ?\DateTimeInterface
    {
        return $this->dateRead;
    }

    public function setDateRead(\DateTimeInterface $dateRead): self
    {
        $this->dateRead = $dateRead;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }
}
