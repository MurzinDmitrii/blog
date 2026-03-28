<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

final readonly class ArticleSummary
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public ?string $imagePath,
        public int $viewCount,
        public string $publishedAt,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['title'],
            isset($row['description']) ? (string) $row['description'] : null,
            isset($row['image_path']) ? (string) $row['image_path'] : null,
            (int) $row['view_count'],
            (string) $row['published_at'],
        );
    }

    public function getDisplayDate(): string
    {
        return (new DateTimeImmutable($this->publishedAt))->format('F j, Y');
    }

    /**
     * @return array<string, mixed>
     */
    public function toTemplateArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image_path' => $this->imagePath,
            'view_count' => $this->viewCount,
            'published_at' => $this->publishedAt,
            'date_display' => $this->getDisplayDate(),
        ];
    }
}
