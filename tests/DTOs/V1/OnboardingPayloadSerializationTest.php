<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\OauthPlayersDTO;
use BrandBridge\DTOs\V1\OnboardingPayload;
use BrandBridge\DTOs\V1\PlayerDetailsDTO;
use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PayloadVersion;
use BrandBridge\Enums\PlayerStatus;

function buildOnboardingPayload(): OnboardingPayload
{
    $now = new DateTimeImmutable('2026-01-15T10:00:00+00:00');

    return new OnboardingPayload(
        version: PayloadVersion::V1,
        sourceBrand: BrandKey::Vegastars,
        sourcePlayerId: 'PLR-001',
        player: new PlayerSnapshot(
            sourcePlayerId: 'PLR-001',
            sourceBrand: BrandKey::Vegastars,
            vipSettingId: 42,
            username: 'testplayer',
            email: 'test@example.com',
            password: null,
            currencyCode: 'GBP',
            status: PlayerStatus::ACTIVE,
            banned: false,
            excluded: false,
            suspended: false,
            kickedAt: null,
            lastLoggedInAt: new DateTimeImmutable('2026-01-14T18:00:00+00:00'),
            phoneVerifiedAt: new DateTimeImmutable('2026-01-10T12:00:00+00:00'),
            betLimit: 1000.0,
            isTestPlayer: false,
            agentCode: null,
            batchId: 'BATCH-1',
            phoneNumber: '+44123456789',
            isHoldWithdrawal: false,
            uuid: '550e8400-e29b-41d4-a716-446655440000',
            lastOnlineAt: new DateTimeImmutable('2026-01-14T19:00:00+00:00'),
            lastOfflineAt: null,
            referredById: null,
            referredByCode: null,
            referredOrderNumber: null,
            preferredCurrency: 'EUR',
            passwordInitiated: true,
            vipRewardSettingId: 7,
            globalId: 'GID-001',
        ),
        details: new PlayerDetailsDTO(
            firstName: 'John',
            lastName: 'Doe',
            middleName: null,
            callingCode: '+44',
            contactNumber: '123456789',
            birthday: new DateTimeImmutable('1990-05-20'),
            language: 'en',
            address: '123 Test St',
            postCode: 'SW1A 1AA',
            nationality: 'GB',
            gender: 'male',
            playerId: 1001,
            signupUrl: 'https://example.com/join',
            countryId: 826,
            stateId: null,
            cityId: null,
            payload: ['tier' => 'gold'],
            registerFrom: 'web',
            queryString: 'ref=test',
            stateName: 'Greater London',
            cityName: 'London',
            timezone: 'Europe/London',
            privateMode: false,
            isPep: false,
            hasSanctions: false,
            customAttributes: ['segment' => 'vip'],
            indicators: ['risk' => 'low'],
        ),
        oauths: [
            new OauthPlayersDTO(
                playerId: 1001,
                providerPlayerId: 'google-sub-abc',
                provider: 'google',
                meta: ['verified_email' => true],
            ),
        ],
        snapshotTakenAt: $now,
    );
}

it('round-trips through toArray/fromArray', function () {
    $original = buildOnboardingPayload();
    $array = $original->toArray();
    $restored = OnboardingPayload::fromArray($array);

    expect($restored->version)->toBe($original->version)
        ->and($restored->sourceBrand)->toBe($original->sourceBrand)
        ->and($restored->sourcePlayerId)->toBe($original->sourcePlayerId)
        ->and($restored->player->email)->toBe($original->player->email)
        ->and($restored->player->status)->toBe($original->player->status)
        ->and($restored->player->uuid)->toBe($original->player->uuid)
        ->and($restored->details->firstName)->toBe($original->details->firstName)
        ->and($restored->details->gender)->toBe($original->details->gender)
        ->and($restored->details->postCode)->toBe($original->details->postCode)
        ->and($restored->details->payload)->toBe($original->details->payload)
        ->and($restored->oauths)->toHaveCount(1)
        ->and($restored->oauths[0]->provider)->toBe($original->oauths[0]->provider)
        ->and($restored->oauths[0]->providerPlayerId)->toBe($original->oauths[0]->providerPlayerId)
        ->and($restored->oauths[0]->meta)->toBe($original->oauths[0]->meta)
        ->and($restored->snapshotTakenAt->format(DATE_RFC3339))->toBe($original->snapshotTakenAt->format(DATE_RFC3339));
});

it('produces a deterministic fingerprint', function () {
    $payload = buildOnboardingPayload();

    $fp1 = $payload->fingerprint();
    $fp2 = $payload->fingerprint();

    expect($fp1)->toBe($fp2)
        ->and(strlen($fp1))->toBe(64);
});

it('fingerprint changes when a field changes', function () {
    $payload1 = buildOnboardingPayload();

    $payload2 = new OnboardingPayload(
        version: $payload1->version,
        sourceBrand: $payload1->sourceBrand,
        sourcePlayerId: 'PLR-DIFFERENT',
        player: $payload1->player,
        details: $payload1->details,
        oauths: $payload1->oauths,
        snapshotTakenAt: $payload1->snapshotTakenAt,
    );

    expect($payload1->fingerprint())->not->toBe($payload2->fingerprint());
});
