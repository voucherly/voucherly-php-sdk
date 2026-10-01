<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'spec'])
    ->notName('index.php');

$config = new PhpCsFixer\Config();
$config->setRiskyAllowed(true);
$config->setRules([
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    '@PHP74Migration' => true,
    '@PHP74Migration:risky' => true,
    '@PHPUnit84Migration:risky' => true,

    'concat_space' => ['spacing' => 'one'],
    'native_constant_invocation' => false,
    'native_function_invocation' => ['strict' => false],
    'ordered_class_elements' => false,
    'phpdoc_align' => false,
    'php_unit_internal_class' => false,
    'php_unit_test_class_requires_covers' => false,
    'php_unit_strict' => false,
    'strict_comparison' => false,
    'declare_strict_types' => false,
    'no_null_property_initialization' => false,
    'phpdoc_no_empty_return' => false,
]);
$config->setFinder($finder);

return $config;
