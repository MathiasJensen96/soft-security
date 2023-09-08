<?php

namespace security;

use Redis;

class RateLimiter
{
    private Redis $redis;

    /**
     * @throws \RedisException
     */
    public function __construct(private int $limit, private int $period)
    {
        $this->redis = new Redis();
        $this->redis->connect('redis', 6379);
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