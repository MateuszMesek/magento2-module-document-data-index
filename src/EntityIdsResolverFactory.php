<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexerApi\EntityIdsResolverInterface;

class EntityIdsResolverFactory
{
    private Config $config;
    private ObjectManagerInterface $objectManager;

    public function __construct(
        Config $config,
        ObjectManagerInterface $objectManager
    )
    {
        $this->config = $config;
        $this->objectManager = $objectManager;
    }

    public function create(string $documentName): EntityIdsResolverInterface
    {
        $type = $this->config->getEntityIdsResolver($documentName);

        $entityIdsResolver = $this->objectManager->create($type);

        if (!$entityIdsResolver instanceof EntityIdsResolverInterface) {
            $interfaceName = EntityIdsResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $entityIdsResolver;
    }
}
