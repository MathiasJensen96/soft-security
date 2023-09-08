<?php

namespace security;

use Redis;

class RateLimiter
{
    private Redis $redis;

    /**
     * @throws \RedisException
     */
    public function __construct(private readonly int $limit, private readonly int $period)
    {
        $this->redis = new Redis();
        $this->redis->connect('redis', 6379);
        $this->redis->auth($_ENV['REDIS_PASSWORD']);
    }

    /**
     * @throws \RedisException
     */
    public function isAllowed(string $key): bool
    {
        $count = $this->redis->get($key);
        if ($count === false) {
            $this->redis->set($key, 1, $this->period);
            return true;
        }
        if ($count < $this->limit) {
            $this->redis->incr($key);
            return true;
        }
        return false;
    }
}