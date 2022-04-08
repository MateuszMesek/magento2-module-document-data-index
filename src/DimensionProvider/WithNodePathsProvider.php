<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex\DimensionProvider;

use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Traversable;

class WithNodePathsProvider implements DimensionProviderInterface
{
    public const DIMENSION_NAME = 'node-paths';

    private array $nodePaths;
    private DimensionProviderInterface $dimensionProvider;
    private DimensionFactory $dimensionFactory;
    private SerializerInterface $serializer;

    public function __construct(
        array                      $nodePaths,
        DimensionProviderInterface $dimensionProvider,
        DimensionFactory           $dimensionFactory,
        SerializerInterface        $serializer
    )
    {
        $this->nodePaths = $nodePaths;
        $this->dimensionProvider = $dimensionProvider;
        $this->dimensionFactory = $dimensionFactory;
        $this->serializer = $serializer;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $dimension = $this->dimensionFactory->create(self::DIMENSION_NAME, $this->serializer->serialize($this->nodePaths));

            $dimensions[$dimension->getName()] = $dimension;

            yield $dimensions;
        }
    }
}
