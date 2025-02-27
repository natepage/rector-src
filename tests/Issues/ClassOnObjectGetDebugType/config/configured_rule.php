<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
use Rector\Php80\Rector\Ternary\GetDebugTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([ClassOnObjectRector::class, GetDebugTypeRector::class]);
};
