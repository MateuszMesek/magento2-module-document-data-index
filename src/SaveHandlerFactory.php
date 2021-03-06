<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexApi\Config\SaveHandlerInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexApi\SaveHandlerInterface;

class SaveHandlerFactory
{
    private ConfigInterface $config;
    private ObjectManagerInterface $objectManager;

    /**
     * @var SaveHandlerInterface[]
     */
    private array $instances = [];

    public function __construct(
        ConfigInterface        $config,
        ObjectManagerInterface $objectManager
    )
    {
        $this->config = $config;
        $this->objectManager = $objectManager;
    }

    public function create(string $documentName): SaveHandlerInterface
    {
        $type = $this->config->getSaveHandler($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Save handler for '$documentName' document data is not configured"
            );
        }

        $saveHandler = $this->objectManager->create($type);

        if (!$saveHandler instanceof SaveHandlerInterface) {
            $interfaceName = SaveHandlerInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $saveHandler;
    }

    public function get(string $documentName): SaveHandlerInterface
    {
        if (!isset($this->instances[$documentName])) {
            $this->instances[$documentName] = $this->create($documentName);
        }

        return $this->instances[$documentName];
    }
}
