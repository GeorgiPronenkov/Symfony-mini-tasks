<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        //get all products
       $products = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->getListCategories();

        return $this->render('default/index.html.twig',
            [
                'products' => $products,
            ]);
    }

    /** new
     * @Route("/new")
     * @param Request $request
     * @return Response
     */
    public function newProduct(Request $request) {

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

//        check form validation
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

           return $this->redirectToRoute('homepage');
        }

        return $this->render('default/edit.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /** edit
     * @Route("/edit/{id}", name="edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editProduct(Request $request, int $id) {

        $product = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->find($id);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/edit.html.twig',
            ['form' => $form->createView()]);
    }

}
