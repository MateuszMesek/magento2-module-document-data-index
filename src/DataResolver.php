<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use MateuszMesek\DocumentDataIndexerApi\DataResolverInterface;
use MateuszMesek\DocumentDataIndexerApi\DimensionResolverInterface;
use Traversable;

class DataResolver implements DataResolverInterface
{
    private DimensionResolverInterface $documentNameResolver;
    private DataResolverFactory $dataResolverFactory;

    public function __construct(
        DimensionResolverInterface $documentNameResolver,
        DataResolverFactory $dataResolverFactory
    )
    {
        $this->documentNameResolver = $documentNameResolver;
        $this->dataResolverFactory = $dataResolverFactory;
    }

    public function resolve(array $dimensions, Traversable $entityIds): Traversable
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->dataResolverFactory->create($documentName)->resolve($dimensions, $entityIds);
    }
}
