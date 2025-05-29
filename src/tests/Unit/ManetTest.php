<?php

use Tilabs\Manet\Facades\Manet;
use Tilabs\Manet\Services\ClassBuilder;

pest()->uses(Tests\TestCase::class);

describe('ClassBuilder Initialization and Basic Addition', function () {
    it('can be initialized with a single class string', function () {
        $list = Manet::classList('class')->__toString();
        expect($list)->toBe('class');
    });

    it('can be initialized with multiple space-separated classes', function () {
        $list = Manet::classList('class1 class2 class3')->__toString();
        expect($list)->toBe('class1 class2 class3');
    });

    it('can add classes through multiple add() calls', function () {
        $list = Manet::classList()
            ->add('class1')
            ->add('class2')
            ->add('class3')
            ->__toString();
        expect($list)->toBe('class1 class2 class3');
    });

    it('can add classes from mixed input types (string, array)', function () {
        // Arr::toCssClasses handles the array conversion internally
        $list = Manet::classList()
            ->add(['class1', 'class2' => true, 'class-false' => false]) // Example of array input
            ->add('class3')
            ->add(['class4', 'class5'])
            ->__toString();
        expect($list)->toBe('class1 class2 class3 class4 class5');
    });

    it('handles empty string and empty array inputs gracefully', function () {
        $list = Manet::classList()
            ->add('')
            ->add([])
            ->add('class1') // To ensure it's not just an empty string result
            ->add('')
            ->__toString();
        expect($list)->toBe('class1'); // Squish will remove extra spaces

        $listEmpty = Manet::classList()->add('')->add([])->__toString();
        expect($listEmpty)->toBe('');
    });

    it('handles null input gracefully', function () {
        $list = Manet::classList()
            ->add(null)
            ->add('class1') // To ensure it's not just an empty string result
            ->add(null)
            ->__toString();
        expect($list)->toBe('class1'); // Squish will remove extra spaces

        $listEmpty = Manet::classList()->add(null)->add(null)->__toString();
        expect($listEmpty)->toBe('');
    });
});

describe('ClassBuilder Output Formatting (__toString)', function () {
    it('correctly trims leading/trailing whitespace and squashes internal whitespace', function () {
        $list = Manet::classList("  class1   class2\tclass3  ")->__toString();
        expect($list)->toBe('class1 class2 class3');
    });

    it('maintains single spaces between classes after multiple adds with extra spacing', function () {
        $list = Manet::classList()
            ->add('  classA  ')
            ->add('  classB   classC  ')
            ->__toString();
        expect($list)->toBe('classA classB classC');
    });
});

describe('ClassBuilder Conditional Addition - when()', function () {
    it('adds classes if condition is a boolean true', function () {
        $list = Manet::classList('base')
            ->when(true, 'conditional-class')
            ->__toString();
        expect($list)->toBe('base conditional-class');
    });

    it('does not add classes if condition is a boolean false and no default is provided', function () {
        $list = Manet::classList('base')
            ->when(false, 'conditional-class')
            ->__toString();
        expect($list)->toBe('base');
    });

    it('adds classes if condition is true using a callable', function () {
        $list = Manet::classList('base')
            ->when(true, fn (ClassBuilder $builder) => $builder->add('callable-class'))
            ->__toString();
        expect($list)->toBe('base callable-class');
    });

    it('does not execute callable if condition is false and no default', function () {
        $callableExecuted = false;
        $list = Manet::classList('base')
            ->when(false, function (ClassBuilder $builder) use (&$callableExecuted) {
                $callableExecuted = true;

                return $builder->add('callable-class');
            })
            ->__toString();
        expect($list)->toBe('base');
        expect($callableExecuted)->toBeFalse();
    });

    it('adds default classes if condition is false', function () {
        $list = Manet::classList('base')
            ->when(false, 'conditional-class', 'default-class')
            ->__toString();
        expect($list)->toBe('base default-class');
    });

    it('adds default classes if condition is false using a callable default', function () {
        $list = Manet::classList('base')
            ->when(false, 'conditional-class', fn (ClassBuilder $builder) => $builder->add('callable-default'))
            ->__toString();
        expect($list)->toBe('base callable-default');
    });

    it('executes primary callable if condition is true, not default callable', function () {
        $defaultCallableExecuted = false;
        $list = Manet::classList('base')
            ->when(
                true,
                fn (ClassBuilder $builder) => $builder->add('primary-callable'),
                function (ClassBuilder $builder) use (&$defaultCallableExecuted) {
                    $defaultCallableExecuted = true;

                    return $builder->add('default-callable');
                }
            )
            ->__toString();
        expect($list)->toBe('base primary-callable');
        expect($defaultCallableExecuted)->toBeFalse();
    });
});

describe('ClassBuilder Conditional Addition - unless()', function () {
    it('does not add classes if condition is true', function () {
        $list = Manet::classList('base')
            ->unless(true, 'conditional-class')
            ->__toString();
        expect($list)->toBe('base');
    });

    it('adds classes if condition is false', function () {
        $list = Manet::classList('base')
            ->unless(false, 'conditional-class')
            ->__toString();
        expect($list)->toBe('base conditional-class');
    });

    it('does not add classes if condition is true using a callable', function () {
        $callableExecuted = false;
        $list = Manet::classList('base')
            ->unless(true, function (ClassBuilder $builder) use (&$callableExecuted) {
                $callableExecuted = true;

                return $builder->add('callable-class');
            })
            ->__toString();

        expect($list)->toBe('base');
        expect($callableExecuted)->toBeFalse();
    });

    it('adds classes if condition is false using a callable', function () {
        $list = Manet::classList('base')
            ->unless(false, fn (ClassBuilder $builder) => $builder->add('callable-class'))
            ->__toString();

        expect($list)->toBe('base callable-class');
    });
});
