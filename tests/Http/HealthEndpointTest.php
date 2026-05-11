<?php

declare(strict_types=1);

it('returns ok status with brand and version', function () {
    $response = $this->getJson('/api/cross-brand/bridge/health');

    $response->assertOk();
    $response->assertJson([
        'status' => 'ok',
        'version' => 'v1',
        'brand' => 'vegastars',
    ]);
});
