<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexer;

use Magento\Framework\Config\DataInterface;

class Config
{
    private DataInterface $data;

    public function __construct(
        DataInterface $data
    )
    {
        $this->data = $data;
    }

    public function getDocumentNames(): array
    {
        $documents = $this->data->get();

        return array_keys($documents);
    }

    public function getDimensionProvider(string $documentName): string
    {
        return $this->data->get("$documentName/dimensionProvider");
    }

    public function getEntityIdsResolver(string $documentName): string
    {
        return $this->data->get("$documentName/entityIdsResolver");
    }

    public function getIndexNamesProvider(string $documentName): ?string
    {
        return $this->data->get("$documentName/indexNamesProvider");
    }

    public function getIndexNameResolver(string $documentName): string
    {
        return $this->data->get("$documentName/indexNameResolver");
    }

    public function getDataResolver(string $documentName): string
    {
        return $this->data->get("$documentName/dataResolver");
    }
}
