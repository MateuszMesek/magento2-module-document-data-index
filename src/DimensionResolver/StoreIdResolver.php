<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer\DimensionResolver;

use Magento\Store\Model\StoreDimensionProvider;
use Magento\Store\Model\StoreManagerInterface;
use MateuszMesek\DocumentIndexerApi\DimensionResolverInterface;

class StoreIdResolver implements DimensionResolverInterface
{
    private StoreManagerInterface $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager
    )
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @param \Magento\Framework\Indexer\Dimension[] $dimensions
     * @return int|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(array $dimensions): ?int
    {
        if (!isset($dimensions[StoreDimensionProvider::DIMENSION_NAME])) {
            return null;
        }

        $store = $dimensions[StoreDimensionProvider::DIMENSION_NAME]->getValue();

        return $this->storeManager->getStore($store)->getId();
    }
}
