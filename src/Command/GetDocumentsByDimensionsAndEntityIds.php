<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer\Command;

use MateuszMesek\DocumentIndexer\Command\GetDocumentsByDimensionsAndEntityIds\Pool;
use MateuszMesek\DocumentIndexerApi\Command\GetDocumentsByDimensionsAndEntityIdsInterface;
use MateuszMesek\DocumentIndexerApi\DimensionResolverInterface;
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
