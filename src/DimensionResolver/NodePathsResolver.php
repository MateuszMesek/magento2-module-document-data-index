<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex\DimensionResolver;

use Magento\Framework\Serialize\SerializerInterface;
use MateuszMesek\DocumentDataIndex\DimensionProvider\WithNodePathsProvider;
use MateuszMesek\DocumentDataIndexApi\DimensionResolverInterface;

class NodePathsResolver implements DimensionResolverInterface
{
    private SerializerInterface $serializer;

    public function __construct(
        SerializerInterface $serializer
    )
    {
        $this->serializer = $serializer;
    }

    /**
     * @param \Magento\Framework\Indexer\Dimension[] $dimensions
     * @return string[]|null
     */
    public function resolve(array $dimensions): ?array
    {
        if (!isset($dimensions[WithNodePathsProvider::DIMENSION_NAME])) {
            return null;
        }

        return $this->serializer->unserialize(
            $dimensions[WithNodePathsProvider::DIMENSION_NAME]->getValue()
        );
    }
}
