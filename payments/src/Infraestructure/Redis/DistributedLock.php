<?php

namespace App\Infraestructure\Redis;

class DistributedLock
{
    public function __construct(private RedisClient $redisClient)
    {
    }

    /**
     * Tries to acquire a lock. Returns the lock token or null when the lock is already taken.
     */
    public function acquire(string $key, int $ttlMilliseconds = 10000): ?string
    {
        $token = bin2hex(random_bytes(16));

        $acquired = $this->redisClient->client()->set(
            $this->redisClient->key($key),
            $token,
            ['nx', 'px' => $ttlMilliseconds]
        );

        return $acquired ? $token : null;
    }

    public function release(string $key, string $token): void
    {
        $script = <<<'LUA'
if redis.call('get', KEYS[1]) == ARGV[1] then
    return redis.call('del', KEYS[1])
end
return 0
LUA;

        $this->redisClient->client()->eval($script, [$this->redisClient->key($key), $token], 1);
    }
}
