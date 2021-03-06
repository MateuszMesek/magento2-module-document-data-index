<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex\Command;

use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndex\Config;
use MateuszMesek\DocumentDataIndexApi\Command\GetEntityIdsByDimensionsInterface;
use MateuszMesek\DocumentDataIndexApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexApi\EntityIdsResolverInterface;
use Traversable;

class GetEntityIdsByDimensions implements GetEntityIdsByDimensionsInterface
{
    private Config $config;
    private DimensionResolverInterface $documentNameResolver;
    private ObjectManagerInterface $objectManager;

    public function __construct(
        Config $config,
        DimensionResolverInterface $documentNameResolver,
        ObjectManagerInterface $objectManager
    )
    {
        $this->config = $config;
        $this->documentNameResolver = $documentNameResolver;
        $this->objectManager = $objectManager;
    }

    public function execute(array $dimensions): Traversable
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $instanceName = $this->config->getEntityIdsResolver($documentName);

        $instance = $this->objectManager->get($instanceName);

        if (!$instance instanceof EntityIdsResolverInterface) {

        }

        return $instance->resolve($dimensions);
    }
}
