<?php
namespace Flagger\Test;

use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function tearDown(): void
    {
        putenv("FLAGGER_API_ENDPOINT=");
        putenv("FLAGGER_ENVIRONMENT_TOKEN=");
    }

    public function testConfigFromArray()
    {
        $config = new \Flagger\Config(['key' => 'value']);
        $this->assertEquals('value', $config->get('key'));
    }

    public function testConfigFromNull()
    {
        $config = new \Flagger\Config(null);
        $this->assertEquals('https://api.useflagger.com/', $config->get('api_endpoint'));
    }

    public function testConfigFromConfig()
    {
        $other = new \Flagger\Config(['key' => 'value']);
        $config = new \Flagger\Config($other);
        $this->assertEquals('value', $config->get('key'));
    }

    public function testAPIEndpointDefaultValue()
    {
        $config = new \Flagger\Config();
        $this->assertEquals('https://api.useflagger.com/', $config->get('api_endpoint'));
    }

    public function testAPIEndpointFromEnvironment()
    {
        putenv("FLAGGER_API_ENDPOINT=http://example.com");

        $config = new \Flagger\Config();
        $this->assertEquals('http://example.com', $config->get('api_endpoint'));
    }

    public function testAPIEndpointFromConstructor()
    {
        $config = new \Flagger\Config(['api_endpoint' => 'http://localhost']);
        $this->assertEquals('http://localhost', $config->get('api_endpoint'));
    }

    public function testEnvironmentTokenDefaultValue()
    {
        $config = new \Flagger\Config();
        $this->assertEquals('', $config->get('environment_token'));
    }

    public function testEnvironmentTokenFromEnvironment()
    {
        putenv("FLAGGER_ENVIRONMENT_TOKEN=tok-environment");

        $config = new \Flagger\Config();
        $this->assertEquals('tok-environment', $config->get('environment_token'));
    }

    public function testEnvironmentTokenFromConstructor()
    {
        $config = new \Flagger\Config(['environment_token' => 'tok-constructor']);
        $this->assertEquals('tok-constructor', $config->get('environment_token'));
    }
}
