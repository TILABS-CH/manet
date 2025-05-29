<?php

declare(strict_types=1);

namespace Tilabs\Manet\Services;

class ManetManager
{
    public function classList(mixed $styles = null)
    {
        $builder = new ClassBuilder;

        return $styles ? $builder->add($styles) : $builder;
    }
}
