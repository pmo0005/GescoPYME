<?php

namespace AppBundle\Repository;

/**
 * EmpresaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmpresaRepository extends \Doctrine\ORM\EntityRepository
{

    public function getPepa()
    {
        $query = $this->createQueryBuilder('e')
            ->where("e.nombre = 'Pepa'");

        return $query->getQuery()->getResult();
    }

}
