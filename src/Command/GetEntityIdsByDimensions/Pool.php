<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer\Command\GetEntityIdsByDimensions;

use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;
use MateuszMesek\DocumentDataIndexerApi\Command\GetEntityIdsByDimensionsInterface;

class Pool
{
    private TMap $documents;

    public function __construct(
        TMapFactory $TMapFactory,
        array $documents = []
    )
    {
        $this->documents = $TMapFactory->createSharedObjectsMap([
            'type' => GetEntityIdsByDimensionsInterface::class,
            'array' => $documents
        ]);
    }

    public function get(string $document): GetEntityIdsByDimensionsInterface
    {
        return $this->documents[$document];
    }
}
