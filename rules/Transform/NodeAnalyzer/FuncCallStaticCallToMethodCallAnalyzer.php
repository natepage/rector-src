<?php

declare(strict_types=1);

namespace Rector\Transform\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\ObjectType;
use Rector\Core\NodeManipulator\ClassDependencyManipulator;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Naming\Naming\PropertyNaming;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Transform\NodeFactory\PropertyFetchFactory;
use Rector\Transform\NodeTypeAnalyzer\TypeProvidingExprFromClassResolver;

final class FuncCallStaticCallToMethodCallAnalyzer
{
    public function __construct(
        private readonly TypeProvidingExprFromClassResolver $typeProvidingExprFromClassResolver,
        private readonly PropertyNaming $propertyNaming,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeFactory $nodeFactory,
        private readonly PropertyFetchFactory $propertyFetchFactory,
        private readonly ClassDependencyManipulator $classDependencyManipulator,
    ) {
    }

    public function matchTypeProvidingExpr(
        Class_ $class,
        ClassMethod $classMethod,
        ObjectType $objectType,
    ): MethodCall | PropertyFetch | Variable {
        $expr = $this->typeProvidingExprFromClassResolver->resolveTypeProvidingExprFromClass(
            $class,
            $classMethod,
            $objectType,
        );

        if ($expr instanceof Expr) {
            if ($expr instanceof Variable) {
                $this->addClassMethodParamForVariable($expr, $objectType, $classMethod);
            }

            return $expr;
        }

        $propertyName = $this->propertyNaming->fqnToVariableName($objectType);
        $this->classDependencyManipulator->addConstructorDependency(
            $class,
            new PropertyMetadata($propertyName, $objectType)
        );

        return $this->propertyFetchFactory->createFromType($objectType);
    }

    private function addClassMethodParamForVariable(
        Variable $variable,
        ObjectType $objectType,
        ClassMethod | Function_ $functionLike
    ): void {
        /** @var string $variableName */
        $variableName = $this->nodeNameResolver->getName($variable);

        // add variable to __construct as dependency
        $functionLike->params[] = $this->nodeFactory->createParamFromNameAndType($variableName, $objectType);
    }
}
