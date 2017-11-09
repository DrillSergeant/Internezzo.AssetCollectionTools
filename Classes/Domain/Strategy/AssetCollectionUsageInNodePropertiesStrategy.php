<?php
namespace Internezzo\AssetCollectionTools\Domain\Strategy;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Neos\Domain\Service\SiteService;
use Neos\Neos\Domain\Strategy\AssetUsageInNodePropertiesStrategy;
use Neos\Utility\TypeHandling;
use Neos\Media\Domain\Model\AssetInterface;


/**
 * @Flow\Scope("singleton")
 */
class AssetCollectionUsageInNodePropertiesStrategy extends AssetUsageInNodePropertiesStrategy
{
    /**
     * Returns all nodes that use an assetCollection (containing the asset) in a node property.
     *
     * @param AssetInterface $asset
     * @return array
     */
    public function getRelatedNodes(AssetInterface $asset)
    {
        $relationMap = [];
        $relationMap[TypeHandling::getTypeForValue($asset)] = [$this->persistenceManager->getIdentifierByObject($asset)];

        if ($asset instanceof Asset) {
            //add relationIdentifiers for assetCollections of this asset
            $relationIdentifiers = [];
            foreach ($asset->getAssetCollections() as $assetCollection) {
                $relationIdentifiers[] = $this->persistenceManager->getIdentifierByObject($assetCollection);
            }
            $relationMap[AssetCollection::class] = $relationIdentifiers;
        }

        return $this->nodeDataRepository->findNodesByPathPrefixAndRelatedEntities(SiteService::SITES_ROOT_PATH, $relationMap);
    }
}
