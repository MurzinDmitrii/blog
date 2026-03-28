<?php

declare(strict_types=1);

namespace App\Domain;

final readonly class ArticleDetail
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public string $body,
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
            (string) $row['body'],
            isset($row['image_path']) ? (string) $row['image_path'] : null,
            (int) $row['view_count'],
            (string) $row['published_at'],
        );
    }

    public function withIncrementedViews(): self
    {
        return new self(
            $this->id,
            $this->title,
            $this->description,
            $this->body,
            $this->imagePath,
            $this->viewCount + 1,
            $this->publishedAt,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toTemplateArray(string $bodyFormatted): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'body' => $this->body,
            'image_path' => $this->imagePath,
            'view_count' => $this->viewCount,
            'published_at' => $this->publishedAt,
            'body_formatted' => $bodyFormatted,
        ];
    }
}
