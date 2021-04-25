<?php

namespace App\Services;

use App\Repositories\ConfigRepository;

class ConfigService
{
    /** @var  ConfigRepository */
    private $configRepository;

    public function __construct()
    {
        $this->configRepository = app(ConfigRepository::class);
    }

    public function getValue($key)
    {
        $config = $this->configRepository->getValue($key);
        if (!$config) {
            return null;
        }
        return $config->value;
    }

    public function setValue($key, $value)
    {
        $this->configRepository->setValue($key, $value);
    }
}
