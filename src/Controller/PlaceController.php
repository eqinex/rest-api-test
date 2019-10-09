<?php
/**
 * Created by PhpStorm.
 * User: eqinex
 * Date: 09.10.19
 * Time: 23:26
 */

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Traits\RepositoryAwareTrait;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
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
    public function getPlaceListAction()
    {
        $places = $this->getPlaceRepository()->findAll();

        return $this->handleView($this->view($places));
    }

    /**
     * @Rest\Get("/places/{placeId}/details")
     * @return Response
     */
    public function getPlaceDetailsAction(Request $request)
    {
        $placeId = $request->get('placeId');

        /** @var Place $place */
        $place = $this->getPlaceRepository()->findOneBy(['id' => $placeId]);

        return $this->handleView($this->view($place));
    }

    /**
     * @Rest\Post("/place/add")
     */
    public function postMovieAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {

            $place
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
            ;

            $this->getEm()->persist($place);
            $this->getEm()->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}