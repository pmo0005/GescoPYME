<?php

namespace AppBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CsvExporterService {
    public function getResponseFromQueryBuilder(QueryBuilder $queryBuilder, $columns, $filename)
    {
        $entities = new ArrayCollection($queryBuilder->getQuery()->getResult());
        $response = new StreamedResponse();
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $response->setCallback(function () use ($entities, $columns, $propertyAccessor) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, $columns);
            while ($entity = $entities->current()) {
                $values = [];
                foreach ($columns as $key => $column) {
                    $values[] = $entity->normalize($column);
                }
                fputcsv($handle, $values);
                $entities->next();
            }
            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        return $response;
    }
}