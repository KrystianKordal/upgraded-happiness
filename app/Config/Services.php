<?php

namespace Config;

use App\Libraries\CoasterStatusCalculator;
use App\Libraries\ConsoleMonitor;
use App\Libraries\Notifier;
use App\Repositories\CoasterRepositoryInterface;
use App\Repositories\RedisCoasterRepository;
use Clue\React\Redis\RedisClient;
use CodeIgniter\Config\BaseService;
use Predis\Client;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function coasterRepository(bool $getShared = true): CoasterRepositoryInterface
    {
        return new RedisCoasterRepository(self::redis());
    }

    public static function redis(bool $getShared = true): Client
    {
        return new Client([
            'scheme'   => getenv('redis.scheme'),
            'host'     => getenv('redis.host'),
            'port'     => getenv('redis.port'),
            'password' => getenv('redis.password'),
            'database' => getenv('redis.database'),
        ]);
    }

    public static function redisAsync(bool $getShared = true): RedisClient
    {
        return new RedisClient(
            sprintf(
                '%s:%s',
                getenv('redis.host'),
                getenv('redis.port')
            )
        );
    }

    public static function coasterStatusCalculator(): CoasterStatusCalculator
    {
        return new CoasterStatusCalculator();
    }

    public static function consoleMonitor(): ConsoleMonitor
    {
        return new ConsoleMonitor(
            self::coasterStatusCalculator(),
            self::notifier()
        );
    }

    public static function notifier(): Notifier
    {
        return new Notifier();
    }
}
