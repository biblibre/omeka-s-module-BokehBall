<?php

namespace BokehBall\Job;

use Omeka\Job\AbstractJob;
use Omeka\Entity\Value as Value;

class UpdateJob extends AbstractJob
{
    protected $properties;

    public function perform()
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $em = $services->get('Omeka\EntityManager');
        $logger = $services->get('Omeka\Logger');
        $args = $this->job->getArgs();

        $bokeh_url = $settings->get('bokehball_bokeh_resource_url');
        $bokeh_bounce_label = $settings->get('bokehball_bokeh_bounce_label', 'Bokeh resource url');
        $selectedProperty = $args['bokehball_bokeh_property_selected'];
        $this->buildPropertiesMap();

        $dql = '
            SELECT r.id id, TRIM(v.value) value FROM Omeka\Entity\Resource r
            JOIN r.values v WITH v.property = :property AND v.type = :type
        ';

        $query = $em->createQuery($dql);
        $query->setParameter('property', $this->getProperty('koha:biblionumber'));
        $query->setParameter('type', 'literal');
        $results = $query->getResult();
        $chunks = array_chunk($results, 1000);
        foreach ($chunks as $results) {
            $records = [];
            foreach ($results as $r) {
                if (!is_numeric($r['value'])) {
                    continue;
                }

                $biblionumber = (int) $r['value'];

                // Check that $biblionumber is a positive integer (not a float)
                if ($biblionumber === 0 || $biblionumber != $r['value']) {
                    continue;
                }
                $resource = $em->getRepository('Omeka\Entity\Resource')->find($r['id']);
                $resourceValues = $resource->getValues();
                $existingProperties = [];
                foreach ($resourceValues as $value) {
                    $property = $value->getProperty();
                    $propertyVocabularyPrefix = $property->getVocabulary()->getPrefix();
                    $propertyLocalName = $property->getLocalName();

                    $uriValue = $value->getUri();
                    if (isset($uriValue)) {
                        $existingProperties["$propertyVocabularyPrefix:$propertyLocalName"] = $uriValue;
                    }
                }

                $toAdd = false;
                if (array_key_exists($selectedProperty, $existingProperties)) {
                    if (!str_contains($existingProperties[$selectedProperty], $bokeh_url)) {
                        $toAdd = true;
                    }
                } else {
                    $toAdd = true;
                }

                if ($toAdd) {
                    $newValue = new Value();
                    $newValue->setResource($resource);
                    $newValue->setProperty($this->getProperty($selectedProperty));
                    $newValue->setType('uri');
                    $newValue->setUri($bokeh_url . $biblionumber);
                    $newValue->setValue($bokeh_bounce_label);

                    $resourceValues->add($newValue);
                    $records[] = $resource;
                }
            }

            $em->flush();
            $logger->info(sprintf('%d resource(s) updated', count($records)));
            foreach ($records as $record) {
                $em->detach($record);
            }
        }
    }

    protected function getProperty(string $term)
    {
        $properties = $this->getProperties();
        if (!isset($properties[$term])) {
            throw new \Exception("Property '$term' does not exist");
        }

        return $properties[$term] ?? null;
    }

    protected function getProperties()
    {
        if (!isset($this->properties)) {
            $this->buildPropertiesMap();
        }

        return $this->properties;
    }
    protected function buildPropertiesMap()
    {
        $services = $this->getServiceLocator();
        $em = $services->get('Omeka\EntityManager');

        $this->properties = [];

        $properties = $em->getRepository('Omeka\Entity\Property')->findAll();
        foreach ($properties as $property) {
            $term = $property->getVocabulary()->getPrefix() . ':' . $property->getLocalName();
            $this->properties[$term] = $property;
        }
    }
}
