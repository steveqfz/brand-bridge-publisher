<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\OauthPlayersDTO;

it('round-trips through toArray and fromArray with meta', function (): void {
    $oauth = new OauthPlayersDTO(
        playerId: 9001,
        providerPlayerId: 'sub-google-xyz',
        provider: 'google',
        meta: ['email_verified' => true, 'picture' => 'https://cdn.example/p.png'],
    );

    $array = $oauth->toArray();
    $restored = OauthPlayersDTO::fromArray($array);

    expect($restored->playerId)->toBe(9001)
        ->and($restored->providerPlayerId)->toBe('sub-google-xyz')
        ->and($restored->provider)->toBe('google')
        ->and($restored->meta)->toBe(['email_verified' => true, 'picture' => 'https://cdn.example/p.png']);
});

it('round-trips with string player id and null meta', function (): void {
    $oauth = new OauthPlayersDTO(
        playerId: 'ext-42',
        providerPlayerId: 'apple-user-id',
        provider: 'apple',
        meta: null,
    );

    $array = $oauth->toArray();
    $restored = OauthPlayersDTO::fromArray($array);

    expect($restored->playerId)->toBe('ext-42')
        ->and($restored->providerPlayerId)->toBe('apple-user-id')
        ->and($restored->provider)->toBe('apple')
        ->and($restored->meta)->toBeNull();
});
