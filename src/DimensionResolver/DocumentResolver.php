<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer\DimensionResolver;

use MateuszMesek\DocumentIndexer\DimensionProvider\WithDocumentProvider;
use MateuszMesek\DocumentIndexerApi\DimensionResolverInterface;

class DocumentResolver implements DimensionResolverInterface
{
    /**
     * @param \Magento\Framework\Indexer\Dimension[] $dimensions
     * @return string|null
     */
    public function resolve(array $dimensions): ?string
    {
        if (!isset($dimensions[WithDocumentProvider::DIMENSION_NAME])) {
            return null;
        }

        return $dimensions[WithDocumentProvider::DIMENSION_NAME]->getValue();
    }
}
