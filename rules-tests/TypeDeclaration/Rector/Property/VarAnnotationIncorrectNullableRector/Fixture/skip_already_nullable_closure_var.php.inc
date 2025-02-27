<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

use Closure;

final class SkipAlreadyNullableClosureVar
{
    /** @var null|Closure(string, int, Closure(float):int):string */
    private ?Closure $tooltipGenerator = null;
}
