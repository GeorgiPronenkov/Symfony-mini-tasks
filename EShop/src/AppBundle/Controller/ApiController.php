<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Doctrine\DBAL\Connection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * Class ApiController
 * @Route("/api")
 * @package AppBundle\Controller
 */
class ApiController extends Controller
{
    /**
     * @Route("/products", methods={"GET"})
     * @return Response
     */
    public function getProducts()
    {
        $products = $this->getDoctrine()
                         ->getRepository(Product::class)
                         ->findAll();

        return $this->response($products);
    }

    /**
     * @Route("/products",methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function createProducts(Request $request)
    {
        //create instance:
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

//        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->response($product);
//        }
//
//        throw new Exception('Form not valid!!!');
    }

    /**
     * @Route("/product/{id}",methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function getProduct(int $id)
    {
        $product = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found.');
        }
        
        return $this->response($product);
    }

    /**
     * @Route("/product/{id}", methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProduct(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->find(Product::class, $id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found...');
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $em->persist($product);
        $em->flush();

        return $this->response($product);
    }

    /**
     * @Route("/product/{id}", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteProduct(int $id)
    {
        $product = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->find($id);

        if (!$product) {
           throw $this->createNotFoundException('Product not found...');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->response('1');
    }

    //country method:

    /**
     * @Route("/countries", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getCountries(Request $request)
    {
        /** @var Connection $db */
        $termSearch = $request->get('term');
        $db = $this->getDoctrine()->getConnection();
        $countries = $db->fetchAll('SELECT country_name 
                                          FROM geography.countries
                                         WHERE country_name LIKE :term
                                         LIMIT 50',
                                         ['term' => $termSearch.'%']
                                  );

        return $this->response($countries);
    }

    //правим преизползаем метод:
    public function response($data): Response
    {
        $serializator = $this->get('jms_serializer');
        return new Response($serializator->serialize($data,'json'), 200,
            ['content-type' => 'application/json']);
    }
}
