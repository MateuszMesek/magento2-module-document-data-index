<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer\Command\GetDocumentsByDimensionsAndEntityIds;

use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;
use MateuszMesek\DocumentIndexerApi\Command\GetDocumentsByDimensionsAndEntityIdsInterface;

class Pool
{
    private TMap $documents;

    public function __construct(
        TMapFactory $TMapFactory,
        array $documents = []
    )
    {
        $this->documents = $TMapFactory->createSharedObjectsMap([
            'type' => GetDocumentsByDimensionsAndEntityIdsInterface::class,
            'array' => $documents
        ]);
    }

    public function get(string $document): GetDocumentsByDimensionsAndEntityIdsInterface
    {
        return $this->documents[$document];
    }
}
