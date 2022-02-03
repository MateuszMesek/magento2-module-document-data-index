<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer\Command;

use MateuszMesek\DocumentIndexer\Command\GetEntityIdsByDimensions\Pool;
use MateuszMesek\DocumentIndexerApi\Command\GetEntityIdsByDimensionsInterface;
use MateuszMesek\DocumentIndexerApi\DimensionResolverInterface;
use Traversable;

class GetEntityIdsByDimensions implements GetEntityIdsByDimensionsInterface
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

    public function execute(array $dimensions): Traversable
    {
        $document = $this->documentResolver->resolve($dimensions);

        return $this->pool->get($document)->execute($dimensions);
    }
}
