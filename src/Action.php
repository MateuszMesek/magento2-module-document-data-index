<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer;

use ArrayIterator;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;
use MateuszMesek\DocumentIndexerApi\Command\GetDocumentsByDimensionsAndEntityIdsInterface;
use MateuszMesek\DocumentIndexerApi\Command\GetEntityIdsByDimensionsInterface;
use MateuszMesek\DocumentIndexerApi\SaveHandlerInterface;
use Traversable;

class Action implements ActionInterface, DimensionalIndexerInterface
{
    private DimensionProviderInterface $dimensionProvider;
    private GetEntityIdsByDimensionsInterface $getEntityIdsByDimensions;
    private GetDocumentsByDimensionsAndEntityIdsInterface $getDocumentsByDimensionsAndEntityIds;
    private SaveHandlerInterface $saveHandler;

    public function __construct(
        DimensionProviderInterface                    $dimensionProvider,
        GetEntityIdsByDimensionsInterface             $getEntityIdsByDimensions,
        GetDocumentsByDimensionsAndEntityIdsInterface $getDocumentsByDimensionsAndEntityIds,
        SaveHandlerInterface                          $saveHandler
    )
    {
        $this->dimensionProvider = $dimensionProvider;
        $this->getEntityIdsByDimensions = $getEntityIdsByDimensions;
        $this->getDocumentsByDimensionsAndEntityIds = $getDocumentsByDimensionsAndEntityIds;
        $this->saveHandler = $saveHandler;
    }

    public function executeFull(): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $ids = $this->getEntityIdsByDimensions->execute($dimensions);

            $this->executeByDimensions($dimensions, $ids);

            // TODO: remove not indexed ids
        }
    }

    public function executeList(array $ids): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $this->executeByDimensions($dimension, new ArrayIterator($ids));
        }
    }

    public function executeRow($id): void
    {
        $this->executeList([$id]);
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds): void
    {
        if (!$this->saveHandler->isAvailable($dimensions)) {
            return;
        }

        $documents = $this->getDocumentsByDimensionsAndEntityIds->execute($dimensions, $entityIds);

        $this->saveHandler->saveIndex(
            $dimensions,
            $documents
        );
    }
}
