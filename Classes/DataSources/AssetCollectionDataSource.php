<?php
namespace Internezzo\AssetCollectionTools\DataSources;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Repository\AssetCollectionRepository;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Flow\Annotations as Flow;
use Neos\Utility\TypeHandling;
class AssetCollectionDataSource extends AbstractDataSource
{
    /**
     * @var string
     */
    static protected $identifier = 'internezzo-assetcollectiontools-assetcollections';

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var AssetCollectionRepository
     */

    protected $assetCollectionRepository;
    /**
     * @param NodeInterface $node
     * @param array $arguments
     * @return array
     */

    public function getData(NodeInterface $node = null, array $arguments)
    {
        $options = [['label' => '-', 'value' => '']];
        $assetCollections = $this->assetCollectionRepository->findAll();
        foreach ($assetCollections as $assetCollection) {
            /** @var AssetCollection $assetCollection */
            $options[] = [
                'label' => $assetCollection->getTitle(),
                'value' => json_encode([
                    '__identity' => $this->persistenceManager->getIdentifierByObject($assetCollection),
                    '__type' => TypeHandling::getTypeForValue($assetCollection)
                ])
            ];
        }
        return $options;
    }
}