<?php
/**
 * Created by PhpStorm.
 * User: eqinex
 * Date: 09.10.19
 * Time: 23:26
 */

namespace App\Controller;

use App\Traits\RepositoryAwareTrait;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Place controller
 * @Rest\Route("/api", name="api_")
 */
class PlaceController extends FOSRestController
{
    use RepositoryAwareTrait;

    /**
     * @Rest\Get("/places")
     * @return Response
     */
    public function getPlaceAction()
    {
        $places = $this->getPlaceRepository()->findAll();

        return $this->handleView($this->view($places));
    }
}