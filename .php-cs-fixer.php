<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'method_chaining_indentation' => true,
        'array_indentation' => true,
        'declare_strict_types' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=' => 'single_space',
            ]
        ],
        'whitespace_after_comma_in_array' => true,
        'phpdoc_to_property_type' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_empty_phpdoc' => true,
        'no_unused_imports' => true
    ])
    ->setFinder($finder)
;
