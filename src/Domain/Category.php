<?php

declare(strict_types=1);

namespace App\Domain;

final readonly class Category
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['name'],
            isset($row['description']) ? (string) $row['description'] : null,
        );
    }

    /**
     * @return array{id:int,name:string,description:?string}
     */
    public function toTemplateArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
