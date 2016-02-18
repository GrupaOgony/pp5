<?php

namespace lanoseoBundle\Controller;

use lanoseoBundle\Entity\Customers;
use lanoseoBundle\Entity\Orders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');
        $mostlyOrderedCars = $this->getDoctrine()->getRepository('lanoseoBundle:Orders');
        $cars = $repository->findAll();
        $orders = $mostlyOrderedCars->findAll();




        return $this->render('lanoseoBundle:Default:index.html.twig', array(

            'orders' => $orders,
            'cars' => $cars,

        ));
    }
    public function contactAction()
    {
        return $this->render('lanoseoBundle:Default:contact.html.twig');
    }

    public function loginAction()
    {
        return $this->render('lanoseoBundle:Default:login.html.twig');
    }

    public function registerAction()
    {
        return $this->render('lanoseoBundle:Default:register.html.twig');
    }
    public function carlistAction()
    {
        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');

        $cars = $repository->findAll();

        return $this->render('lanoseoBundle:Default:carlist.html.twig', array(

            'cars' => $cars,

        ));
    }

    public function errorAction()
    {
        return $this->render('lanoseoBundle:Default:error.html.twig');
    }

    public function placeOrderAction(Request $carRequest)
    {
        $carId = $carRequest->get('carId');
        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');

        $car = $repository->findBy(array('carId' => $carId ));

        return $this->render('lanoseoBundle:Default:place_order.html.twig', array(

            'car' => $car,

        ));
    }

    public function rejectPaymentAction()
    {
        return $this->render('lanoseoBundle:Default:reject_payment.html.twig');
    }

    public function successPaymentAction()
    {
        return $this->render('lanoseoBundle:Default:success_payment.html.twig');
    }

    public function summaryAction(Request $request)
    {


        $place = $request ->request->get('place');
        $fromDate = $request ->request->get('fromDate');
        $toDate = $request ->request->get('toDate');
        $carId = $request->get('carId');
        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');
        $car = $repository->findBy(array('carId' => $carId ));


        $customerRepo = $this ->getDoctrine()->getRepository('lanoseoBundle:Customers');
        $customer = $customerRepo->findAll();
        $selectedCustomer = $customer[0];

        $nowDate = new \DateTime();
        $fromDateOrder = \DateTime::createFromFormat('m/d/Y g:i A', $fromDate);
        $toDateOrder = \DateTime::createFromFormat('m/d/Y g:i A', $toDate);

        $order = new Orders();
        $order -> setOrderDate($nowDate);
        $order -> setOrderFrom($fromDateOrder);
        $order -> setOrderTo($toDateOrder);
        $order -> setOrderPayment(false);
        $order -> setOrderPrice(153);
        $order -> setCustomer($selectedCustomer);
        $order -> setCar($car[0]);
//dodawanie rekordów!
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($order);
//        $em->flush();

        $me = $this -> getDoctrine() -> getEntityManager();
        $con = $me->getConnection();
        $query = $con->prepare("SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
        $query->execute();
        $result = $query->fetchAll();



        return $this->render('lanoseoBundle:Default:summary.html.twig', array(

            'place' => $place,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'car' => $car,
            'result' => $result,

        ));
    }



}
