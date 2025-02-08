<?php declare(strict_types = 1);

namespace PHPStan\ExtensionInstaller;

/**
 * This class is generated by phpstan/extension-installer.
 * @internal
 */
final class GeneratedConfig
{

	public const EXTENSIONS = array (
  'codeigniter/phpstan-codeigniter' => 
  array (
    'install_path' => '/Applications/XAMPP/xamppfiles/htdocs/workspace/crud-api/vendor/codeigniter/phpstan-codeigniter',
    'relative_install_path' => '../../../codeigniter/phpstan-codeigniter',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'extension.neon',
      ),
    ),
    'version' => '1.x-dev 430e0b4',
    'phpstanVersionConstraint' => '>=2.0.0.0-dev, <3.0.0.0-dev',
  ),
  'phpstan/phpstan-strict-rules' => 
  array (
    'install_path' => '/Applications/XAMPP/xamppfiles/htdocs/workspace/crud-api/vendor/phpstan/phpstan-strict-rules',
    'relative_install_path' => '../../phpstan-strict-rules',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'rules.neon',
      ),
    ),
    'version' => '2.0.3',
    'phpstanVersionConstraint' => '>=2.0.4.0-dev, <3.0.0.0-dev',
  ),
  'shipmonk/phpstan-baseline-per-identifier' => 
  array (
    'install_path' => '/Applications/XAMPP/xamppfiles/htdocs/workspace/crud-api/vendor/shipmonk/phpstan-baseline-per-identifier',
    'relative_install_path' => '../../../shipmonk/phpstan-baseline-per-identifier',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'extension.neon',
      ),
    ),
    'version' => '2.1.3',
    'phpstanVersionConstraint' => '>=2.0.0.0-dev, <3.0.0.0-dev',
  ),
);

	public const NOT_INSTALLED = array (
);

	/** @var string|null */
	public const PHPSTAN_VERSION_CONSTRAINT = '>=2.0.4.0-dev, <3.0.0.0-dev';

	private function __construct()
	{
	}

}
