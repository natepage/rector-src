<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SkipNullableMockObject extends TestCase
{
    /**
     * @var DateTime&MockObject
     */
    private $property;

    public function testExample(): void
    {
        $this->property = $this->createMock(DateTime::class);
        $this->property->expects(self::once())->method('format');
        $this->property->format('Y');
    }
}
