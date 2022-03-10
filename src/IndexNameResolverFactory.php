<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexerApi\IndexNameResolverInterface;

class IndexNameResolverFactory
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

    public function create(string $documentName): IndexNameResolverInterface
    {
        $type = $this->config->getIndexNameResolver($documentName);

        $indexNameResolver = $this->objectManager->create($type);

        if (!$indexNameResolver instanceof IndexNameResolverInterface) {
            $interfaceName = IndexNameResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $indexNameResolver;
    }
}