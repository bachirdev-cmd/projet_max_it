<?php
namespace App\Core;

use Symfony\Component\Yaml\Yaml;

class App
{
    private static array $dependencies = [];

    public static function init()
    {
        $configPath = __DIR__ . '/../../config/services.yml';
        if (!file_exists($configPath)) {
            throw new \RuntimeException('Le fichier services.yml est introuvable');
        }
        $services = Yaml::parseFile($configPath);
        self::$dependencies = $services['services'];
    }

    public static function getDependency($key)
    {
        if (empty(self::$dependencies)) {
            self::init();
        }
        if (array_key_exists($key, self::$dependencies)) {
            $className = self::$dependencies[$key]['class'];
            if (class_exists($className)) {
                if (method_exists($className, 'getInstance')) {
                    return $className::getInstance();
                }
                return new $className();
            }
        }
        return null;
    }
}
