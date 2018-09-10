<?php

namespace Shopsys\ShopBundle\Component\EntityExtension;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Joschi127\DoctrineEntityOverrideBundle\EventListener\LoadORMMetadataSubscriber as BaseLoadORMMetadataSubscriber;

class LoadORMMetadataSubscriber extends BaseLoadORMMetadataSubscriber
{
    /**
     * Setup of association mappings for child classes by parent classes
     * Original state:
     *  - association mapping of child class properties is always replaced by parent class association mapping for every property
     *
     * New state:
     *  - Mapping of child class property is overridden only if:
     *      - either mapping of child class property is equal to mapping of parent class property
     *      - either if mapping of child class property is not equal to mapping of parent class property but child class property does not override some parent class property
     *  - If mapping of child class property is not equal to mapping of parent class property and child class property overrides parent class property, there will be used mapping of child class property
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata
     * @param $configuration
     */
    protected function setAssociationMappings(ClassMetadataInfo $metadata, $configuration)
    {
        foreach ($this->overriddenEntities as $interface => $class) {
            $class = $this->getClass($class);

            if ($class === $metadata->getName()) {
                foreach ($this->parentClassesByClass[$class] as $parentClass) {
                    $parentMetadata = $this->getClassMetadata($parentClass, $configuration);

                    if (in_array($parentClass, $configuration->getMetadataDriverImpl()->getAllClassNames(), true)) {
                        $configuration->getMetadataDriverImpl()->loadMetadataForClass($parentClass, $parentMetadata);

                        if ($this->classIsOverridden($parentClass)) {
                            foreach ($parentMetadata->getAssociationMappings() as $name => $mapping) {
                                if (isset($mapping['sourceEntity']) && $mapping['sourceEntity'] == $parentClass) {
                                    $mapping['sourceEntity'] = $class;
                                }

                                // if target entity is provided by relative classname, make it absolute
                                if (!class_exists($mapping['targetEntity']) && strpos($mapping['targetEntity'], '\\') === false) {
                                    $reflClass = new \ReflectionClass($parentClass);
                                    $namespace = $reflClass->getNamespaceName();
                                    $absoluteTargetEntity = $namespace . '\\' . $mapping['targetEntity'];
                                    if (class_exists($absoluteTargetEntity)) {
                                        $mapping['targetEntity'] = $absoluteTargetEntity;
                                    }
                                }

                                // custom behavior as described in the annotation of the method
                                $isDifferenceBetweenChildAssociationMappingAndParentAssociationMapping = $metadata->associationMappings[$name] !== $mapping;

                                if ($isDifferenceBetweenChildAssociationMappingAndParentAssociationMapping) {
                                    $overriddingClassReflection = new \ReflectionClass($class);
                                    $overiddingClassProperties = $overriddingClassReflection->getProperties();

                                    $isOverriddenPropertyInChildClass = $this->checkIsOverriddenPropertyInChildClass($overiddingClassProperties, $name, $class);
                                }

                                if (!$isDifferenceBetweenChildAssociationMappingAndParentAssociationMapping || !$isOverriddenPropertyInChildClass) {
                                    $metadata->associationMappings[$name] = $mapping;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param \ReflectionProperty[] $overiddingClassProperties
     * @param string $parentClassPropertyName
     * @param string $parentClassName
     * @return bool
     */
    private function checkIsOverriddenPropertyInChildClass(array $overiddingClassProperties, $parentClassPropertyName, $parentClassName)
    {
        foreach ($overiddingClassProperties as $overiddingClassProperty) {
            if ($overiddingClassProperty->name === $parentClassPropertyName && $overiddingClassProperty->class === $parentClassName) {
                return true;
            }
        }
        return false;
    }
}
