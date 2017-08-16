<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Roles;

class RoleController extends FOSRestController
{
    /**
    * @Rest\Get("/role")
    */
    public function getAction()
    {
        $result = $this->getDoctrine()->getRepository('AppBundle:Roles')->findAll();
        if ($result === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $result;
    }

    /**
    * @Rest\Get("/role/{id}")
    */
    public function idAction($id)
    {
        $result = $this->getDoctrine()->getRepository('AppBundle:Roles')->find($id);
        if ($result === null) {
            return new View("role not found", Response::HTTP_NOT_FOUND);
        }
        return $result;
    }

    /**
    * @Rest\Post("/role/")
    */
    public function postAction(Request $request)
    {
        $data = new Roles;
        $name = $request->get('name');
        if(empty($name)) {
            return new View("null values are not allowed", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setName($name);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("role added successfully", Response::HTTP_OK);
    }

    /**
    * @Rest\Put("/role/{id}")
    */
    public function updateAction($id, Request $request)
    { 
        $data = new Roles;
        $name = $request->get('name');
        $sn = $this->getDoctrine()->getManager();
        $role = $this->getDoctrine()->getRepository('AppBundle:Roles')->find($id);
        if (empty($role)) {
            return new View("role not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($name)) {
            $role->setName($name);
            $sn->flush();
            return new View("role Updated Successfully", Response::HTTP_OK);
        }
        else return new View("role name cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
    * @Rest\Delete("/role/{id}")
    */
    public function deleteAction($id)
    {
        $data = new Roles;
        $sn = $this->getDoctrine()->getManager();
        $role = $this->getDoctrine()->getRepository('AppBundle:Roles')->find($id);
        if (empty($role)) {
            return new View("role not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($role);
            $sn->flush();
        }
        return new View("role deleted successfully", Response::HTTP_OK);
    }

}