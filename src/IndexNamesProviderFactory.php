<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexApi\IndexNamesProviderInterface;

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

        if (null === $type) {
            throw new InvalidArgumentException(
                "Index names provider for '$documentName' document data is not configured"
            );
        }

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
