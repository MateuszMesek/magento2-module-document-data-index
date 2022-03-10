<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexerApi\DataResolverInterface;

class DataResolverFactory
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

    public function create(string $documentName): DataResolverInterface
    {
        $type = $this->config->getDataResolver($documentName);

        $dataResolver = $this->objectManager->create($type);

        if (!$dataResolver instanceof DataResolverInterface) {
            $interfaceName = DataResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $dataResolver;
    }
}
