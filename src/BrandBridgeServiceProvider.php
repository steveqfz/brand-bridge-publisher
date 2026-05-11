<?php

declare(strict_types=1);

namespace BrandBridge;

use BrandBridge\Contracts\EligibilityCheckerInterface;
use BrandBridge\Contracts\Mappers\BettingLimitMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerDetailsMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerSnapshotMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerTagMapperInterface;
use BrandBridge\Contracts\Mappers\SelfExclusionMapperInterface;
use BrandBridge\Contracts\Mappers\VipCommentMapperInterface;
use BrandBridge\Contracts\Mappers\VipContactMapperInterface;
use BrandBridge\Contracts\Mappers\VipGroupMapperInterface;
use BrandBridge\Contracts\Mappers\VipManagerMapperInterface;
use BrandBridge\Contracts\Mappers\VipMilestoneMapperInterface;
use BrandBridge\Contracts\Mappers\VipProfileMapperInterface;
use BrandBridge\Contracts\Mappers\VipPromiseMapperInterface;
use BrandBridge\Contracts\Mappers\VipReminderMapperInterface;
use BrandBridge\Exceptions\MapperNotPublishedException;
use BrandBridge\Services\DefaultEligibilityChecker;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class BrandBridgeServiceProvider extends ServiceProvider
{
    private const MAPPER_BINDINGS = [
        PlayerSnapshotMapperInterface::class => 'App\\BrandBridge\\Mappers\\PlayerSnapshotMapper',
        PlayerDetailsMapperInterface::class  => 'App\\BrandBridge\\Mappers\\PlayerDetailsMapper',
        PlayerTagMapperInterface::class      => 'App\\BrandBridge\\Mappers\\PlayerTagMapper',
        BettingLimitMapperInterface::class   => 'App\\BrandBridge\\Mappers\\BettingLimitMapper',
        SelfExclusionMapperInterface::class  => 'App\\BrandBridge\\Mappers\\SelfExclusionMapper',
        VipProfileMapperInterface::class     => 'App\\BrandBridge\\Mappers\\VipProfileMapper',
        VipGroupMapperInterface::class       => 'App\\BrandBridge\\Mappers\\VipGroupMapper',
        VipManagerMapperInterface::class     => 'App\\BrandBridge\\Mappers\\VipManagerMapper',
        VipCommentMapperInterface::class     => 'App\\BrandBridge\\Mappers\\VipCommentMapper',
        VipMilestoneMapperInterface::class   => 'App\\BrandBridge\\Mappers\\VipMilestoneMapper',
        VipPromiseMapperInterface::class     => 'App\\BrandBridge\\Mappers\\VipPromiseMapper',
        VipReminderMapperInterface::class    => 'App\\BrandBridge\\Mappers\\VipReminderMapper',
        VipContactMapperInterface::class     => 'App\\BrandBridge\\Mappers\\VipContactMapper',
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/brand-bridge.php', 'brand-bridge');

        $this->app->bind(
            EligibilityCheckerInterface::class,
            DefaultEligibilityChecker::class,
        );

        foreach (self::MAPPER_BINDINGS as $interface => $concrete) {
            $this->app->bind($interface, static function () use ($interface, $concrete) {
                if (!class_exists($concrete)) {
                    throw new MapperNotPublishedException(sprintf(
                        'Mapper [%s] is not published. Expected class [%s]. Run "php artisan vendor:publish --tag=brand-bridge-stubs" and implement the mapper.',
                        class_basename($interface),
                        $concrete,
                    ));
                }

                return app($concrete);
            });
        }
    }

    public function boot(): void
    {
        if (config('brand-bridge.publisher.enabled', false)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            $this->configureRateLimiter();
        }

        $this->publishes([
            __DIR__ . '/../config/brand-bridge.php' => config_path('brand-bridge.php'),
        ], 'brand-bridge-config');

        $this->publishes($this->stubMappings(), 'brand-bridge-mappers');
    }

    /** @return array<string, string> */
    private function stubMappings(): array
    {
        $base = __DIR__ . '/Stubs';
        $target = app_path('BrandBridge/Mappers');

        return [
            "$base/player-snapshot-mapper.stub" => "$target/PlayerSnapshotMapper.php",
            "$base/player-details-mapper.stub"  => "$target/PlayerDetailsMapper.php",
            "$base/player-tag-mapper.stub"      => "$target/PlayerTagMapper.php",
            "$base/betting-limit-mapper.stub"   => "$target/BettingLimitMapper.php",
            "$base/self-exclusion-mapper.stub"   => "$target/SelfExclusionMapper.php",
            "$base/vip-profile-mapper.stub"     => "$target/VipProfileMapper.php",
            "$base/vip-group-mapper.stub"       => "$target/VipGroupMapper.php",
            "$base/vip-manager-mapper.stub"     => "$target/VipManagerMapper.php",
            "$base/vip-comment-mapper.stub"     => "$target/VipCommentMapper.php",
            "$base/vip-milestone-mapper.stub"   => "$target/VipMilestoneMapper.php",
            "$base/vip-promise-mapper.stub"     => "$target/VipPromiseMapper.php",
            "$base/vip-reminder-mapper.stub"    => "$target/VipReminderMapper.php",
            "$base/vip-contact-mapper.stub"     => "$target/VipContactMapper.php",
        ];
    }

    private function configureRateLimiter(): void
    {
        $perMinute = (int) config('brand-bridge.publisher.rate_limit_per_minute', 60);
        RateLimiter::for('brand-bridge', static fn(Request $request) => Limit::perMinute($perMinute)->by($request->ip()));
    }
}
