<?php

class Autoloader
{
  public static function register()
  {
    spl_autoload_register(array(__CLASS__, 'autoload'));
    spl_autoload_register(array(__CLASS__, 'autoloadModels'));
    spl_autoload_register(array(__CLASS__, 'autoloadValidations'));
  }

  public static function autoload($className)
  {
    $classFile = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($classFile)) {
      require_once $classFile;
    }
  }

  public static function autoloadModels($className)
  {
    $classFile = 'models/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($classFile)) {
      require_once $classFile;
    }
  }

  public static function autoloadValidations($className)
  {
    $classFile = 'validations/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($classFile)) {
      require_once $classFile;
    }
  }
}
