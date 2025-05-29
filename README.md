# Manet by Tilabs

> ⚡️ A simple and fluent way to add css classes to your Laravel blade components

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

**Requirements**

|                                | Version |
| ------------------------------ | ------- |
| PHP                            | ^8.2    |
| [Laravel](https://laravel.com) | ^12.0   |

## Installation & Usage

```bash

composer require tilabs/manet

```

Publish Test files

```bash

php artisan vendor:publish --tag=tilabs-manet-tests

```

## Usage

Manet provides a `ManetManager` accessible via the `Manet` facade. The primary method you'll use is `classList()`, which returns a `ClassBuilder` instance.

### Basic Usage

```php
use Tilabs\Manet\Facades\Manet;

// Simple class addition
$classes = Manet::classList()->add('p-4 rounded shadow');
// Output: "p-4 rounded shadow"

// Chaining add()
$classes = Manet::classList()
    ->add('text-blue-500')
    ->add('font-bold');
// Output: "text-blue-500 font-bold"

// Initial classes
$classes = Manet::classList('base-class another-base')
    ->add('extra-class');
// Output: "base-class another-base extra-class"
```

### Adding Multiple Classes

You can pass an array of classes to `add()`:

```php
$classes = Manet::classList()->add(['p-4', 'bg-gray-100', 'rounded']);
// Output: "p-4 bg-gray-100 rounded"
```

### Conditional Classes with `add()`

Leverage Laravel's array-to-CSS-classes format:

```php
$isActive = true;
$hasError = false;

$classes = Manet::classList('btn')
    ->add([
        'btn-active' => $isActive,
        'btn-primary' => $isActive && !$hasError,
        'btn-danger' => $hasError,
        'opacity-50' => !$isActive,
    ]);
// If $isActive = true, $hasError = false: "btn btn-active btn-primary"
// If $isActive = false, $hasError = true: "btn btn-danger opacity-50"
```

### Conditional Logic with `when()`

The `when()` method adds classes if the given condition is true.

```php
$isPrimary = true;
$isLarge = false;

$classes = Manet::classList('button')
    ->when($isPrimary, 'button-primary')
    ->when($isLarge, 'button-large', 'button-medium'); // Optional default if condition is false

// If $isPrimary = true, $isLarge = false: "button button-primary button-medium"
```

#### `when()` with a Callable

For more complex logic, pass a closure as the second argument. The closure will receive the `ClassBuilder` instance and the condition's result.

```php
$userType = 'admin';

$classes = Manet::classList('user-profile')
    ->when($userType === 'admin', function ($classBuilder) {
        return $classBuilder->add('bg-red-500 text-white p-2');
    })
    ->when($userType === 'editor', function ($classBuilder) {
        return $classBuilder->add('bg-yellow-300 p-1');
    });

// If $userType = 'admin': "user-profile bg-red-500 text-white p-2"
```

### Conditional Logic with `unless()`

The `unless()` method is the inverse of `when()`. It adds classes if the given condition is false.

```php
$isDisabled = false;

$classes = Manet::classList('form-input')
    ->unless($isDisabled, 'focus:ring-2 focus:ring-blue-500');

// If $isDisabled = false: "form-input focus:ring-2 focus:ring-blue-500"
```

#### `unless()` with a Callable

Similar to `when()`, `unless()` also supports callables.

```php
$isComplete = true;

$classes = Manet::classList('task-item')
    ->unless($isComplete, function ($classBuilder) {
        return $classBuilder->add('border-l-4 border-orange-400');
    });

// If $isComplete = true: "task-item"
// If $isComplete = false: "task-item border-l-4 border-orange-400"
```

## License

Manet is open‑sourced software licensed under the **MIT license**.
