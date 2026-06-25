# Ary

**Ary** is a lightweight, zero-dependency PHP class that gives arrays and objects a single, unified interface. It's a handy alternative to plain arrays or `stdClass` for storing configs, options, registries, environment variables, and any data whose shape changes conditionally — or whenever you want to chain operations over an array.

It's so convenient that, after a while, you'll miss it in projects that don't have it.

## Features

- Access items with either object (`->`) or array (`['']`) syntax — interchangeably.
- Missing keys return `null` (or a default you choose) instead of emitting a notice.
- Get and set values inside deeply nested arrays using **"dot" notation**.
- Behaves like a regular array: works with `count()`, `unset()`, `foreach`, and `json_encode()`.
- Handy helpers: `get`, `has`, `all`, `toArray`, `toObject`, `only`, `except`, `replace`, `replaceRecursively`, and `ary` (sub-ary).
- **No dependencies** — just PHP. A single small class with no framework required.

## Installation

Install with Composer:

```bash
composer require salarmehr/ary
```

Requires **PHP 5.6** or above.

## Usage

### Instantiation

```php
$ary = new Ary();
// or, using the helper:
$ary = ary();
```

### Initialization

```php
$ary = ary(2, 4, 6, 8);              // from a list of values
$ary = ary([2, 4, 6, 8]);            // from an array
$ary = ary(['x' => 'foo', 'y' => 'bar']);
```

### Assignment

```php
$ary->newItem = 20;     // object syntax
$ary['newItem'] = 20;   // array syntax
```

### Retrieval

```php
$foo = $ary->x;         // object syntax
$foo = $ary['x'];       // array syntax

// supply a default for missing keys (no notice, no error):
$value = $ary->get('missing', 'Default value');

$ary->all();            // returns the underlying plain PHP array
```

### Works like a regular array

```php
count($ary);
unset($ary[0]);
json_encode($ary);

foreach ($ary as $key => $value) {
    // ...
}
```

### Deep (dot-notation) access

```php
$ary = ary(['products' => ['desk' => ['price' => 100]]]);

$price = $ary['products.desk.price'];   // 100

$ary['products.table.weight'] = 200;    // creates the nested path
```

### A few of the helpers

```php
$ary->only(['x', 'y']);          // a new Ary with only those keys
$ary->except(['x']);             // a new Ary without those keys
$ary->replace(['x' => 'new']);   // shallow replace, returns a new Ary
$ary->toObject();                // cast to a stdClass
$ary->ary('products');           // a sub-Ary for a nested key
```

## License

MIT
