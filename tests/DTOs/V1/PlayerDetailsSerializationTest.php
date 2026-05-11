<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\PlayerDetailsDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $details = new PlayerDetailsDTO(
        firstName: 'John',
        lastName: 'Doe',
        middleName: 'Q',
        callingCode: '+356',
        contactNumber: '99123456',
        birthday: new DateTimeImmutable('1990-05-15'),
        language: 'en',
        address: '123 Main Street',
        postCode: 'VLT 1234',
        nationality: 'MT',
        gender: 'male',
        playerId: 5001,
        signupUrl: 'https://example.com/register',
        countryId: 470,
        stateId: 10,
        cityId: 100,
        payload: ['source' => 'affiliate'],
        registerFrom: 'mobile',
        queryString: 'utm=test',
        stateName: 'South Eastern',
        cityName: 'Valletta',
        timezone: 'Europe/Malta',
        privateMode: false,
        isPep: false,
        hasSanctions: false,
        customAttributes: ['tier' => 'gold'],
        indicators: ['aml' => 'clear'],
    );

    $array = $details->toArray();
    $restored = PlayerDetailsDTO::fromArray($array);

    expect($restored->firstName)->toBe('John')
        ->and($restored->lastName)->toBe('Doe')
        ->and($restored->middleName)->toBe('Q')
        ->and($restored->callingCode)->toBe('+356')
        ->and($restored->contactNumber)->toBe('99123456')
        ->and($restored->birthday->format('Y-m-d'))->toBe('1990-05-15')
        ->and($restored->language)->toBe('en')
        ->and($restored->address)->toBe('123 Main Street')
        ->and($restored->postCode)->toBe('VLT 1234')
        ->and($restored->nationality)->toBe('MT')
        ->and($restored->gender)->toBe('male')
        ->and($restored->playerId)->toBe(5001)
        ->and($restored->signupUrl)->toBe('https://example.com/register')
        ->and($restored->countryId)->toBe(470)
        ->and($restored->stateId)->toBe(10)
        ->and($restored->cityId)->toBe(100)
        ->and($restored->payload)->toBe(['source' => 'affiliate'])
        ->and($restored->registerFrom)->toBe('mobile')
        ->and($restored->queryString)->toBe('utm=test')
        ->and($restored->stateName)->toBe('South Eastern')
        ->and($restored->cityName)->toBe('Valletta')
        ->and($restored->timezone)->toBe('Europe/Malta')
        ->and($restored->privateMode)->toBeFalse()
        ->and($restored->isPep)->toBeFalse()
        ->and($restored->hasSanctions)->toBeFalse()
        ->and($restored->customAttributes)->toBe(['tier' => 'gold'])
        ->and($restored->indicators)->toBe(['aml' => 'clear']);
});

it('round-trips with nullable fields set to null', function (): void {
    $details = new PlayerDetailsDTO(
        firstName: null,
        lastName: null,
        middleName: null,
        callingCode: null,
        contactNumber: null,
        birthday: null,
        language: null,
        address: null,
        postCode: null,
        nationality: null,
        gender: null,
        playerId: null,
        signupUrl: null,
        countryId: null,
        stateId: null,
        cityId: null,
        payload: null,
        registerFrom: null,
        queryString: null,
        stateName: null,
        cityName: null,
        timezone: null,
        privateMode: false,
        isPep: false,
        hasSanctions: false,
        customAttributes: null,
        indicators: null,
    );

    $array = $details->toArray();
    $restored = PlayerDetailsDTO::fromArray($array);

    expect($restored->firstName)->toBeNull()
        ->and($restored->lastName)->toBeNull()
        ->and($restored->middleName)->toBeNull()
        ->and($restored->callingCode)->toBeNull()
        ->and($restored->contactNumber)->toBeNull()
        ->and($restored->birthday)->toBeNull()
        ->and($restored->language)->toBeNull()
        ->and($restored->address)->toBeNull()
        ->and($restored->postCode)->toBeNull()
        ->and($restored->nationality)->toBeNull()
        ->and($restored->gender)->toBeNull()
        ->and($restored->playerId)->toBeNull()
        ->and($restored->signupUrl)->toBeNull()
        ->and($restored->countryId)->toBeNull()
        ->and($restored->stateId)->toBeNull()
        ->and($restored->cityId)->toBeNull()
        ->and($restored->payload)->toBeNull()
        ->and($restored->registerFrom)->toBeNull()
        ->and($restored->queryString)->toBeNull()
        ->and($restored->stateName)->toBeNull()
        ->and($restored->cityName)->toBeNull()
        ->and($restored->timezone)->toBeNull()
        ->and($restored->customAttributes)->toBeNull()
        ->and($restored->indicators)->toBeNull();
});
