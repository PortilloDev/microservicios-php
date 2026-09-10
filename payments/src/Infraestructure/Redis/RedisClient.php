<?php

namespace App\Infraestructure\Redis;

class RedisClient
{
    private \Redis $redis;

    public function __construct(string $redisUrl, private string $redisKeyPrefix)
    {
        $parts = parse_url($redisUrl) ?: [];
        $host = $parts['host'] ?? 'ecommerce-ms-redis-service';
        $port = isset($parts['port']) ? (int) $parts['port'] : 6379;

        $this->redis = new \Redis();
        $this->redis->connect($host, $port, 2.0);

        if (!empty($parts['pass'])) {
            $this->redis->auth($parts['pass']);
        }

        if (!empty($parts['path']) && '/' !== $parts['path']) {
            $this->redis->select((int) ltrim($parts['path'], '/'));
        }
    }

    public function client(): \Redis
    {
        return $this->redis;
    }

    public function key(string $key): string
    {
        return $this->redisKeyPrefix.$key;
    }
}
