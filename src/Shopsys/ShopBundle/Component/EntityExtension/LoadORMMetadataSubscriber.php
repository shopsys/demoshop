<?php

namespace Shopsys\ShopBundle\Component\EntityExtension;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Joschi127\DoctrineEntityOverrideBundle\EventListener\LoadORMMetadataSubscriber as BaseLoadORMMetadataSubscriber;

class LoadORMMetadataSubscriber extends BaseLoadORMMetadataSubscriber
{
    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();

        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $wasMappedSuperclass = $metadata->isMappedSuperclass;
        $this->setIsMappedSuperclass($metadata);

        if (!$metadata->isMappedSuperclass) {
            $this->setCustomRepositoryClasses($metadata, $eventArgs->getEntityManager()->getConfiguration());
            $this->setFieldMappings($metadata, $eventArgs->getEntityManager()->getConfiguration(), $em);
        } else {
            $this->unsetAssociationMappings($metadata);
            $this->unsetFieldMappings($metadata, $wasMappedSuperclass);
        }

        $this->updateAssociationMappingsToMappedSuperclasses($metadata);
    }
}
