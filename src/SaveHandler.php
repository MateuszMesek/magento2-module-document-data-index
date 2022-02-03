<?php declare(strict_types=1);

namespace MateuszMesek\DocumentIndexer;

use Magento\Framework\App\ResourceConnection;
use MateuszMesek\Document\Api\Command\GetDocumentNodesInterface;
use MateuszMesek\DocumentIndexerApi\DimensionResolverInterface;
use MateuszMesek\DocumentIndexerApi\IndexNameResolverInterface;
use MateuszMesek\DocumentIndexerApi\SaveHandlerInterface;
use Traversable;

class SaveHandler implements SaveHandlerInterface
{
    private IndexNameResolverInterface $indexNameResolver;
    private DimensionResolverInterface $documentResolver;
    private GetDocumentNodesInterface $getDocumentNodes;
    private ResourceConnection $resourceConnection;
    private string $connectionName;

    public function __construct(
        IndexNameResolverInterface $indexNameResolver,
        DimensionResolverInterface $documentResolver,
        GetDocumentNodesInterface $getDocumentNodes,
        ResourceConnection $resourceConnection,
        string $connectionName = null
    )
    {
        $this->indexNameResolver = $indexNameResolver;
        $this->documentResolver = $documentResolver;
        $this->getDocumentNodes = $getDocumentNodes;
        $this->resourceConnection = $resourceConnection;
        $this->connectionName = $connectionName ?: ResourceConnection::DEFAULT_CONNECTION;
    }

    public function isAvailable($dimensions = []): bool
    {
        $connection = $this->resourceConnection->getConnectionByName($this->connectionName);

        return $connection->isTableExists(
            $this->getTableName($dimensions)
        );
    }

    public function saveIndex($dimensions, Traversable $documents): void
    {
        $document = $this->documentResolver->resolve($dimensions);

        $documentNodes = $this->getDocumentNodes->execute($document);

        $paths = [];

        foreach ($documentNodes as $documentNode) {
            $paths[] = $documentNode['path'];
        }

        $connection = $this->resourceConnection->getConnection($this->connectionName);

        foreach ($documents as $document) {
            $data = [];

            foreach ($paths as $path) {
                $data[] = [
                    'document_id' => $document['id'],
                    'path'=> $path,
                    'value' => $document[$path]
                ];
            }

            $connection->delete(
                $this->getTableName($dimensions),
                [
                    'document_id = ?' => $document['id'],
                    'path NOT IN (?)' => $paths
                ]
            );
            $connection->insertOnDuplicate(
                $this->getTableName($dimensions),
                $data,
                ['value']
            );
        }
    }

    public function deleteIndex($dimensions, Traversable $documents): void
    {
        $documentIds = [];

        foreach ($documents as $document) {
            $documentIds[] = $document['id'];
        }

        $connection = $this->resourceConnection->getConnection($this->connectionName);
        $connection->delete(
            $this->getTableName($dimensions),
            [
                'document_id IN (?)' => $documentIds
            ]
        );
    }

    private function getTableName(array $dimensions): string
    {
        return $this->resourceConnection->getTableName(
            $this->indexNameResolver->resolve($dimensions),
            $this->connectionName
        );
    }
}
