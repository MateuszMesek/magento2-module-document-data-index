<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer\DimensionProvider;

use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Traversable;

class WithDocumentProvider implements DimensionProviderInterface
{
    public const DIMENSION_NAME = 'document';

    private string $document;
    private DimensionProviderInterface $dimensionProvider;
    private DimensionFactory $dimensionFactory;

    public function __construct(
        string $document,
        DimensionProviderInterface $dimensionProvider,
        DimensionFactory $dimensionFactory
    )
    {
        $this->document = $document;
        $this->dimensionProvider = $dimensionProvider;
        $this->dimensionFactory = $dimensionFactory;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $dimension = $this->dimensionFactory->create(self::DIMENSION_NAME, $this->document);

            $dimensions[$dimension->getName()] = $dimension;

            yield $dimensions;
        }
    }
}
