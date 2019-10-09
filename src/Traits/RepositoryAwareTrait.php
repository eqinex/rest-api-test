<?php
/**
 * Created by PhpStorm.
 * User: eqinex
 * Date: 09.10.19
 * Time: 23:30
 */

namespace App\Traits;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;

trait RepositoryAwareTrait
{
    /**
     * @return Registry
     */
    abstract protected function getDoctrine();

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return PlaceRepository
     */
    protected function getPlaceRepository()
    {
        return $this->getDoctrine()->getRepository(Place::class);
    }
}