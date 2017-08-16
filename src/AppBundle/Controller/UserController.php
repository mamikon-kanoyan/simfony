<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Users;
use AppBundle\Entity\Roles;

class UserController extends FOSRestController
{
    /**
    * @Rest\Get("/user")
    */
    public function getAction()
    {
        $result = $this->getDoctrine()->getRepository('AppBundle:Users')->findAll();
        if ($result === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $result;
    }

    /**
    * @Rest\Get("/user/{id}")
    */
    public function idAction($id)
    {
        $result = $this->getDoctrine()->getRepository('AppBundle:Users')->find($id);
        if ($result === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $result;
    }

    /**
    * @Rest\Post("/user/")
    */
    public function postAction(Request $request)
    {
        $data = new Users;
        $name = $request->get('name');
        $role_id = $request->get('role_id');
        if(empty($name) || empty($role_id)) {
            return new View("null values are not allowed", Response::HTTP_NOT_ACCEPTABLE); 
        }
        $role = $this->getDoctrine()->getRepository('AppBundle:Roles')->find($role_id);
        if (empty($role)) {
            return new View("role not found", Response::HTTP_NOT_FOUND);
        }
        $data->setName($name);
        $data->setRoleId($role_id);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("user added successfully", Response::HTTP_OK);
    }

    /**
    * @Rest\Put("/user/{id}")
    */
    public function updateAction($id, Request $request)
    { 
        $data = new Users;
        $name = $request->get('name');
        $role_id = $request->get('role_id');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Users')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($name) && !empty($role_id)) {
            $user->setName($name);
            $user->setRoleId($role_id);
            $sn->flush();
            return new View("user updated Successfully", Response::HTTP_OK);
        }
        elseif(empty($name) && !empty($role_id)) {
            $user->setRoleId($role_id);
            $sn->flush();
            return new View("role updated successfully", Response::HTTP_OK);
        }
        elseif(!empty($name) && empty($role_id)) {
            $user->setName($name);
            $sn->flush();
            return new View("user name updated successfully", Response::HTTP_OK);
        }
        else return new View("user name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
    * @Rest\Delete("/user/{id}")
    */
    public function deleteAction($id)
    {
        $data = new Users;
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Users')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($user);
            $sn->flush();
        }
        return new View("user deleted successfully", Response::HTTP_OK);
    }

}