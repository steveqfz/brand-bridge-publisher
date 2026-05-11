<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipCommentDTO
{
    public function __construct(
        /** Unique identifier for the comment */
        public string $commentId,
        /** Name of the comment author */
        public string $authorName,
        /** Comment text content */
        public string $content,
        /** Timestamp when the comment was created */
        public \DateTimeImmutable $createdAt,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            commentId: (string) $data['comment_id'],
            authorName: (string) $data['author_name'],
            content: (string) $data['content'],
            createdAt: new \DateTimeImmutable($data['created_at']),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'comment_id' => $this->commentId,
            'author_name' => $this->authorName,
            'content' => $this->content,
            'created_at' => $this->createdAt->format(\DATE_RFC3339),
        ];
    }
}
