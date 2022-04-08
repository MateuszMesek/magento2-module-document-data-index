<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex\Command;

use MateuszMesek\DocumentDataIndex\Config;
use MateuszMesek\DocumentDataIndex\IndexNamesProviderFactory;
use MateuszMesek\DocumentDataIndexApi\Command\GetIndexNamesInterface;
use Traversable;

class GetIndexNames implements GetIndexNamesInterface
{
    private Config $config;
    private IndexNamesProviderFactory $indexNamesProviderFactory;

    public function __construct(
        Config $config,
        IndexNamesProviderFactory $indexNamesProviderFactory
    )
    {
        $this->config = $config;
        $this->indexNamesProviderFactory = $indexNamesProviderFactory;
    }

    public function execute(): Traversable
    {
        $documentNames = $this->config->getDocumentNames();

        foreach ($documentNames as $documentName) {
            $indexesNameProvider = $this->indexNamesProviderFactory->create($documentName);

            yield from $indexesNameProvider->getIndexNames();
        }
    }
}
