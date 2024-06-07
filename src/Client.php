<?php

namespace Flagger;

use \Flagger\Flags\Flags;
use \Flagger\Flags\Request\Entities\Context\Context;
use \Flagger\Flags\Request\Entities\DefaultValue;
use \Flagger\Shared\HttpClient;
use \Flagger\Shared\InMemoryCache;

class Client
{
    private InMemoryCache $cache;
    private ?Context $context;
    private ?array $defaults = [];
    private Flags $flags;

    public static function config(array $config = null) : Client
    {
        return new Client($config);
    }

    public function __construct($config = null)
    {
        $config = new Config($config);
        $client = new HttpClient($config);

        $this->cache = InMemoryCache::getInstance();
        $this->flags = new Flags($client);
    }

    public function withContext(Context $context): Client
    {
        $this->context = $context;
        return $this;
    }

    public function withDefault(string $key, string $type, string|bool|float|int $defaultValue): Client
    {
        array_push($this->defaults, new DefaultValue($key, $type, $defaultValue));
        return $this;
    }

    public function connect(): Client
    {
        $flags = $this->flags->all($this->context, $this->defaults);
        foreach($flags as $flag) {
            $this->cache->set($flag->key, $flag, 60);
        }

        return $this;
    }

    public function all() {
        $results = $this->cache->all();

        $flagKeys = join(',', array_map(function ($item) { return $item->key; }, $results));
        $this->flags->report($flagKeys);

        return $results;
    }

    public function get(string $key) : \Flagger\Flags\Response\Entities\Flag
    {
        $flag = $this->cache->get($key);
        if ($flag == null) {
            $flag = $this->getFlag($this->context, $key, $this->getDefaultValue($key));
        }

        // Report the flag usage.
        $this->flags->report($key);

        return $flag;
    }

    private function getDefaultValue(string $key) : ?DefaultValue
    {
        foreach($this->defaults as $default) {
            if ($default->key == $key) {
                return $default;
            }
        }

        return null;
    }

    private function getFlag(Context $context = null, string $key, ?DefaultValue $defaultValue) : \Flagger\Flags\Response\Entities\Flag
    {
        return $this->flags->single($context, $key, $defaultValue?->type, $defaultValue?->value);
    }
}