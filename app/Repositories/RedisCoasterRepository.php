<?php

namespace App\Repositories;

use App\Entities\Coaster;
use Clue\React\Redis\RedisClient;
use Predis\Client;
use Ramsey\Uuid\UuidInterface;

class RedisCoasterRepository implements CoasterRepositoryInterface
{
    private const REDIS_KEY = 'data:coaster:%s';

    public function __construct(
        private Client $redis
    )
    {
    }

    public function add(Coaster $coaster): void
    {
        $this->redis->set(sprintf(self::REDIS_KEY, $coaster->getId()), json_encode($coaster->toArray()));
        $this->redis->publish('coaster:update', '');
    }

    public function update(Coaster $coaster): void
    {
        $this->redis->set(sprintf(self::REDIS_KEY, $coaster->getId()), json_encode($coaster->toArray()));
        $this->redis->publish('coaster:update', '');
    }

    public function find(UuidInterface $id): ?Coaster
    {
        $data = $this->redis->get(sprintf(self::REDIS_KEY, $id));

        return $data ? Coaster::restore(json_decode($data, true)) : null;
    }

    public function getAll(): array
    {
        $coasters = [];
        $keys = $this->redis->keys(sprintf(self::REDIS_KEY, '*'));

        foreach ($keys as $key) {
            $coaster = $this->redis->get($key);
            if ($coaster) {
                $coasters[] = Coaster::restore(json_decode($coaster, true));
            }
        }

        return $coasters;
    }

    public function delete(Coaster $coaster): void
    {
        $this->redis->del(sprintf(self::REDIS_KEY, $coaster->getId()));
        $this->redis->publish('coaster:update', '');
    }
}