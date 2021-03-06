<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex\DimensionResolver;

use MateuszMesek\DocumentDataIndex\DimensionProvider\WithDocumentNameProvider;
use MateuszMesek\DocumentDataIndexApi\DimensionResolverInterface;

class DocumentNameResolver implements DimensionResolverInterface
{
    /**
     * @param \Magento\Framework\Indexer\Dimension[] $dimensions
     * @return string|null
     */
    public function resolve(array $dimensions): ?string
    {
        if (!isset($dimensions[WithDocumentNameProvider::DIMENSION_NAME])) {
            return null;
        }

        return $dimensions[WithDocumentNameProvider::DIMENSION_NAME]->getValue();
    }
}
