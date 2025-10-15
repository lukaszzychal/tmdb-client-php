<?php

declare(strict_types=1);

/**
 * PHP CS Fixer Configuration for TMDB Client PHP
 * 
 * This configuration provides comprehensive code formatting rules that:
 * - Enforce PSR-12 and PSR-1 standards
 * - Ensure consistent code style across the project
 * - Work without conflicts with other tools
 * - Provide optimal performance and readability
 * 
 * @author Łukasz Zychal <lukasz.zychal.dev@gmail.com>
 * @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer
 */

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        // === PSR Standards ===
        '@PSR12' => true,
        '@PSR1' => true,
        
        // === Array Syntax ===
        'array_syntax' => ['syntax' => 'short'],
        'no_trailing_comma_in_singleline_array' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        
        // === Blank Lines ===
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try', 'if', 'switch', 'for', 'foreach', 'while', 'do'],
        ],
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
                'use_trait',
            ],
        ],
        'single_blank_line_at_eof' => true,
        'single_line_after_imports' => true,
        
        // === Casts ===
        'cast_spaces' => true,
        'lowercase_cast' => true,
        'short_scalar_cast' => true,
        'modernize_types_casting' => true,
        
        // === Comments ===
        'single_line_comment_style' => [
            'comment_types' => ['hash'],
        ],
        'no_empty_comment' => true,
        'no_trailing_whitespace_in_comment' => true,
        
        // === Control Structures ===
        'control_structure_continuation_position' => [
            'position' => 'next_line',
        ],
        'no_unneeded_control_parentheses' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        
        // === Function and Method Calls ===
        'function_declaration' => [
            'closure_function_spacing' => 'one',
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => false,
        ],
        'no_spaces_after_function_name' => true,
        'no_spaces_inside_parenthesis' => true,
        
        // === Imports ===
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'single_import_per_statement' => true,
        
        // === Operators ===
        'concat_space' => [
            'spacing' => 'one',
        ],
        'increment_style' => ['style' => 'post'],
        'object_operator_without_whitespace' => true,
        'operator_linebreak' => [
            'only_booleans' => true,
            'position' => 'end',
        ],
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'unary_operator_spaces' => true,
        
        // === PHP Tags ===
        'blank_line_after_opening_tag' => true,
        'echo_tag_syntax' => ['format' => 'short'],
        'full_opening_tag' => true,
        'linebreak_after_opening_tag' => true,
        
        // === PHPDoc ===
        'phpdoc_align' => [
            'align' => 'vertical',
            'tags' => ['param', 'return', 'throws', 'type', 'var'],
        ],
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        
        // === Return ===
        'return_type_declaration' => true,
        'simplified_null_return' => true,
        
        // === Semicolon ===
        'no_singleline_whitespace_before_semicolons' => true,
        'space_after_semicolon' => true,
        
        // === Spacing ===
        'no_spaces_around_offset' => true,
        'no_whitespace_in_blank_line' => true,
        'no_trailing_whitespace' => true,
        
        // === String ===
        'single_quote' => true,
        'no_mixed_echo_print' => ['use' => 'echo'],
        
        // === Variables ===
        'no_unused_imports' => true,
        'self_accessor' => true,
        
        // === Visibility ===
        'visibility_required' => [
            'elements' => ['method', 'property'],
        ],
        
        // === Risky Rules (enabled with setRiskyAllowed(true)) ===
        'declare_strict_types' => true,
        'dir_constant' => true,
        'ereg_to_preg' => true,
        'function_to_constant' => true,
        'get_class_to_class_keyword' => true,
        'is_null' => true,
        'logical_operators' => true,
        'modernize_strpos' => true,
        'native_constant_invocation' => [
            'fix_built_in' => false,
            'include' => ['DIRECTORY_SEPARATOR', 'PHP_EOL', 'PHP_INT_MAX', 'PHP_INT_SIZE', 'PHP_SAPI', 'PHP_VERSION'],
            'scope' => 'namespaced',
        ],
        'native_function_casing' => true,
        'native_function_invocation' => [
            'include' => ['@compiler_optimized'],
            'scope' => 'namespaced',
            'strict' => true,
        ],
        'no_alias_functions' => true,
        'no_homoglyph_names' => true,
        'non_printable_character' => true,
        'no_php4_constructor' => true,
        'no_unneeded_final_method' => true,
        'no_useless_sprintf' => true,
        'ordered_traits' => true,
        'php_unit_construct' => true,
        'php_unit_dedicate_assert' => true,
        'php_unit_expectation' => true,
        'php_unit_mock' => true,
        'php_unit_mock_short_will_return' => true,
        'php_unit_namespaced' => true,
        'php_unit_no_expectation_annotation' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'alpha',
        ],
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'set_type_to_cast' => true,
        'string_line_ending' => true,
        'strict_param' => true,
        'void_return' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude([
                'vendor',
                'node_modules',
                'coverage',
                '.git',
                '.github',
                'build',
                'dist',
                'docs',
            ])
            ->name('*.php')
            ->notName([
                '*.blade.php',
                '*.twig.php',
                '*.phtml',
                '*.inc',
                '*.install',
                '*.module',
            ])
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    )
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setUsingCache(true);
