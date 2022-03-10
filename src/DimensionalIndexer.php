<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexerApi\DataResolverInterface;
use MateuszMesek\DocumentDataIndexerApi\SaveHandlerInterface;
use Traversable;

class DimensionalIndexer implements DimensionalIndexerInterface
{
    private DataResolverInterface $dataResolver;
    private SaveHandlerInterface $saveHandler;

    public function __construct(
        DataResolverInterface $dataResolver,
        SaveHandlerInterface  $saveHandler
    )
    {
        $this->dataResolver = $dataResolver;
        $this->saveHandler = $saveHandler;
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds): void
    {
        if (!$this->saveHandler->isAvailable($dimensions)) {
            return;
        }

        $documents = $this->dataResolver->resolve($dimensions, $entityIds);

        $this->saveHandler->saveIndex(
            $dimensions,
            $documents
        );
    }
}
