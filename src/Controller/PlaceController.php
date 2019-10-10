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
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $place = $this->getPlaceRepository()->find($placeId);

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

            return $this->handleView($this->view(['id' => $place->getId()], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * @Rest\Put("/place/update")
     */
    public function updatePlaceAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $place = $this->getPlaceRepository()->find($data['id']);

        if (!$place) {
            return new JsonResponse(['status' => 'not_found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {

            $place->setUpdatedAt(new \DateTime());

            $this->getEm()->merge($place);
            $this->getEm()->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
        }
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * @Rest\Delete("/places/{placeId}/delete")
    */
    public function deleteMovieAction(Request $request)
    {
        $placeId = $request->get('placeId');
        /** @var Place $place */
        $place = $this->getPlaceRepository()->findOneBy(['id' => $placeId]);

        $resp = [];

        $resp[] = [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'address' => $place->getAddress(),
            'createdAt' => $place->getCreatedAt()->format('d.m.Y h:i'),
            'updatedAt' => $place->getUpdatedAt()->format('d.m.Y h:i')
        ];

        $this->getEm()->remove($place);
        $this->getEm()->flush();

        return $this->handleView($this->view($resp, Response::HTTP_OK));
    }
}