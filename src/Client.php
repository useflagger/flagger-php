<?php

namespace Flagger;

use \Flagger\Settings\Settings;
use \Flagger\Settings\Request\Entities\Context\Context;
use \Flagger\Settings\Request\Entities\DefaultValue;
use \Flagger\Shared\HttpClient;
use \Flagger\Shared\InMemoryCache;

class Client
{
    private InMemoryCache $cache;
    private ?Context $context;
    private ?array $defaults = [];
    private Settings $settings;

    public static function config(array $config = null) : Client
    {
        return new Client($config);
    }

    public function __construct($config = null)
    {
        $config = new Config($config);
        $client = new HttpClient($config);

        $this->cache = InMemoryCache::getInstance();
        $this->settings = new Settings($client);
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
        $settings = $this->settings->all($this->context, $this->defaults);
        foreach($settings as $setting) {
            $this->cache->set($setting->key, $setting, 60);
        }

        return $this;
    }

    public function all() {
        $results = $this->cache->all();

        $settingKeys = join(',', array_map(function ($item) { return $item->key; }, $results));
        $this->settings->report($settingKeys);

        return $results;
    }

    public function get(string $key) : ?\Flagger\Settings\Response\Entities\Setting
    {
        $setting = $this->cache->get($key);
        if ($setting == null) {
            $setting = $this->getSetting($this->context, $key, $this->getDefaultValue($key));
        }

        // Report the setting usage.
        $this->settings->report($key);

        return $setting;
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

    private function getSetting(Context $context = null, string $key, ?DefaultValue $defaultValue) : \Flagger\Settings\Response\Entities\Setting
    {
        return $this->settings->single($context, $key, $defaultValue?->type, $defaultValue?->value);
    }
}