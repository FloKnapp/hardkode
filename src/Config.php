<?php

namespace Hardkode;

use Hardkode\Exception\FileNotFoundException;

/**
 * Class Config
 * @package Hardkode\Config
 */
class Config
{

    /** @var array */
    private $config;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->loadConfig();
        $this->loadDotEnv();
    }

    /**
     * @param string $namespace
     * @return mixed|null
     */
    public function get(string $namespace)
    {
        if ($pos = strpos($namespace, ':') !== false) {
            return $this->resolve($namespace);
        }

        return $this->config[$namespace] ?? null;
    }

    /**
     * @param string $namespace
     * @return mixed|null
     */
    private function resolve(string $namespace)
    {
        $parts   = explode(':', $namespace);
        $pointer = $this->config;

        foreach ($parts as $part) {
            $pointer = $pointer[$part] ?? null;
        }

        return $pointer;
    }

    /**
     * @return mixed
     * @throws FileNotFoundException
     */
    private function loadConfig()
    {
        $configFile = __DIR__ . '/../config/app.conf.php';

        if (!file_exists($configFile)) {
            throw new FileNotFoundException('Missing configuration file.');
        }

        $this->config = require_once $configFile;
    }

    /**
     * Load .env file
     */
    private function loadDotEnv()
    {
        $dotEnvFile = __DIR__ . '/../.env';

        if (file_exists($dotEnvFile)) {

            $dotEnvContents = file_get_contents($dotEnvFile);
            $lines = explode(PHP_EOL, $dotEnvContents);

            array_walk($lines, function($entry) {

                if (strpos($entry, '=') === false) {
                    return;
                }

                putenv($entry);

            });

        }

    }

}