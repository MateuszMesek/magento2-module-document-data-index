<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex;

use MateuszMesek\DocumentDataIndexApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexApi\IndexNameResolverInterface;

class IndexNameResolver implements IndexNameResolverInterface
{
    private DimensionResolverInterface $documentNameResolver;
    private IndexNameResolverFactory $indexNameResolverFactory;

    public function __construct(
        DimensionResolverInterface $documentNameResolver,
        IndexNameResolverFactory $indexNameResolverFactory
    )
    {
        $this->documentNameResolver = $documentNameResolver;
        $this->indexNameResolverFactory = $indexNameResolverFactory;
    }

    public function resolve(array $dimensions): string
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->indexNameResolverFactory->create($documentName)->resolve($dimensions);
    }
}
