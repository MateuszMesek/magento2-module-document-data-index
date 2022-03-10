<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer\DimensionProvider;

use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Traversable;

class WithDocumentNameProvider implements DimensionProviderInterface
{
    public const DIMENSION_NAME = 'document';

    private string $documentName;
    private DimensionProviderInterface $dimensionProvider;
    private DimensionFactory $dimensionFactory;

    public function __construct(
        string                     $documentName,
        DimensionProviderInterface $dimensionProvider,
        DimensionFactory           $dimensionFactory
    )
    {
        $this->documentName = $documentName;
        $this->dimensionProvider = $dimensionProvider;
        $this->dimensionFactory = $dimensionFactory;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $dimension = $this->dimensionFactory->create(self::DIMENSION_NAME, $this->documentName);

            $dimensions[$dimension->getName()] = $dimension;

            yield $dimensions;
        }
    }
}
