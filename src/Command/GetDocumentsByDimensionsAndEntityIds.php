<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer\Command;

use MateuszMesek\DocumentDataIndexer\Command\GetDocumentsByDimensionsAndEntityIds\Pool;
use MateuszMesek\DocumentDataIndexerApi\Command\GetDocumentsByDimensionsAndEntityIdsInterface;
use MateuszMesek\DocumentDataIndexerApi\DimensionResolverInterface;
use Traversable;

class GetDocumentsByDimensionsAndEntityIds implements GetDocumentsByDimensionsAndEntityIdsInterface
{
    private Pool $pool;
    private DimensionResolverInterface $documentResolver;

    public function __construct(
        Pool $pool,
        DimensionResolverInterface $documentResolver
    )
    {
        $this->pool = $pool;
        $this->documentResolver = $documentResolver;
    }

    public function execute(array $dimensions, Traversable $entityIds): Traversable
    {
        $document = $this->documentResolver->resolve($dimensions);

        return $this->pool->get($document)->execute($dimensions, $entityIds);
    }
}
