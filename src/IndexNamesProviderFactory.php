<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexerApi\IndexNamesProviderInterface;

class IndexNamesProviderFactory
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

    public function create(string $documentName): IndexNamesProviderInterface
    {
        $type = $this->config->getIndexNamesProvider($documentName);
        $arguments = [];

        if (!$type) {
            $type = IndexNamesProvider::class;
            $arguments = ['documentName' => $documentName];
        }

        $indexesNameProvider = $this->objectManager->create($type, $arguments);

        if (!$indexesNameProvider instanceof IndexNamesProviderInterface) {
            $interfaceName = IndexNamesProviderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $indexesNameProvider;
    }
}
