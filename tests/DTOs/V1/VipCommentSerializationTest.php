<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipCommentDTO;

it('round-trips through toArray and fromArray', function (): void {
    $comment = new VipCommentDTO(
        commentId: 'cmt-001',
        authorName: 'VIP Manager Alice',
        content: 'Player prefers phone contact in the morning.',
        createdAt: new DateTimeImmutable('2024-02-10T09:30:00+00:00'),
    );

    $array = $comment->toArray();
    $restored = VipCommentDTO::fromArray($array);

    expect($restored->commentId)->toBe('cmt-001')
        ->and($restored->authorName)->toBe('VIP Manager Alice')
        ->and($restored->content)->toBe('Player prefers phone contact in the morning.')
        ->and($restored->createdAt->format(DATE_RFC3339))->toBe('2024-02-10T09:30:00+00:00');
});
