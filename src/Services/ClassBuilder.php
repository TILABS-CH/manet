<?php

declare(strict_types=1);

namespace Tilabs\Manet\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Stringable;

class ClassBuilder implements Stringable
{
    protected $buffer = [];

    /**
     * Add classes to the buffer.
     *
     * @param  mixed  $classes
     */
    public function add($classes): ClassBuilder
    {
        $this->buffer[] = Arr::toCssClasses($classes);

        return $this;
    }

    /**
     * Add classes if the condition is true.
     *
     * @param  mixed  $classes
     * @param  mixed  $default
     */
    public function when(mixed $condition, string|array|callable|null $classes = null, string|array|callable|null $default = null): self
    {
        $source = $condition ? $classes : $default;

        if (is_callable($source)) {
            return $source($this, $condition);
        } elseif ($source !== null) {
            return $this->add($source);
        }

        return $this;
    }

    /**
     * Add classes unless the condition is true.
     *
     * @param  mixed  $classes
     */
    public function unless(mixed $condition, string|array|callable|null $classes = null): self
    {
        return $this->when(! $condition, $classes);
    }

    /**
     * Transform the buffer into a string representation of CSS classes.
     */
    public function __toString(): string
    {
        return (string) Str::squish(collect($this->buffer)->join(' '));
    }
}
