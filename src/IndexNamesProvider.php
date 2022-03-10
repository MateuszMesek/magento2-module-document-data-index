<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use MateuszMesek\DocumentDataIndexer\Action\DimensionProviderFactory;
use MateuszMesek\DocumentDataIndexerApi\IndexNameResolverInterface;
use MateuszMesek\DocumentDataIndexerApi\IndexNamesProviderInterface;
use Traversable;

class IndexNamesProvider implements IndexNamesProviderInterface
{
    private DimensionProviderFactory $dimensionProviderFactory;
    private IndexNameResolverInterface $indexNameResolver;
    private string $documentName;

    public function __construct(
        DimensionProviderFactory $dimensionProviderFactory,
        IndexNameResolverInterface $indexNameResolver,
        string $documentName
    )
    {
        $this->dimensionProviderFactory = $dimensionProviderFactory;
        $this->indexNameResolver = $indexNameResolver;
        $this->documentName = $documentName;
    }

    public function getIndexNames(): Traversable
    {
        $dimensionProvider = $this->dimensionProviderFactory->create($this->documentName);

        foreach ($dimensionProvider->getIterator() as $dimensions) {
            yield $this->indexNameResolver->resolve($dimensions);
        }
    }
}
