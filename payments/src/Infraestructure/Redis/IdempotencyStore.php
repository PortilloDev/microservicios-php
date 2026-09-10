<?php

namespace App\Infraestructure\Redis;

class IdempotencyStore
{
    public function __construct(private RedisClient $redisClient)
    {
    }

    public function acquire(string $key, int $ttlSeconds): bool
    {
        return (bool) $this->redisClient->client()->set(
            $this->redisClient->key($key),
            '1',
            ['nx', 'ex' => $ttlSeconds]
        );
    }

    public function remember(string $key, string $value, int $ttlSeconds): void
    {
        $this->redisClient->client()->setex($this->redisClient->key($key), $ttlSeconds, $value);
    }

    public function get(string $key): ?string
    {
        $value = $this->redisClient->client()->get($this->redisClient->key($key));

        return false === $value ? null : $value;
    }

    public function forget(string $key): void
    {
        $this->redisClient->client()->del($this->redisClient->key($key));
    }
}
