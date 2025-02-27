<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypedPropertyFromStrictConstructorReadonlyClassRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::TYPED_PROPERTIES);
};
