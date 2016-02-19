<?php

namespace lanoseoBundle\Controller;

use lanoseoBundle\Entity\Customers;
use lanoseoBundle\Entity\Orders;
use lanoseoBundle\Entity\Cars;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;


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

    public function registerAction(Request $registerData)
    {
        $customerName = $registerData->get('customerName');
        $customerSurname = $registerData->get('customerSurname');
        $customerEmail = $registerData->get('customerEmail');
        $customerPassword = $registerData->get('customerPassword');

        // 0 - maila nie ma w bazie danych, 1 - mail jest w bazie danych, 2 - rejestracja ok, 3 - wypełnij pola
        $registerCheck = 0;

        $customerRepository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');

        $customer = $customerRepository->findBy(array('customerEmail' => $customerEmail ));


        if(count($customer) == 1) {
            $registerCheck = 1;
        } else {
            if(isset($_POST['signin'])) {
                if(empty($_POST['customerEmail']) || empty($_POST['customerName']) || empty($_POST['customerSurname']) || empty($_POST['customerPassword'])) {
                    $registerCheck = 3;
                } else {
                    $registerCheck = 2;
                    $customer = new Customers();
                    $customer -> setCustomerName($customerName);
                    $customer -> setCustomerSurname($customerSurname);
                    $customer -> setCustomerEmail($customerEmail);
                    $customer -> setCustomerPassword(sha1($customerPassword));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($customer);
                    $em->flush();
                }

            }

        }

        return $this->render('lanoseoBundle:Default:register.html.twig', array(

            'registerCheck' => $registerCheck,
            'customerEmail' => $customerEmail,
            'customerName' => $customerName,
            'customerSurname' => $customerSurname,

        ));
    }
    public function carlistAction()
    {
        $nowDate = new \DateTime();
        $me = $this -> getDoctrine() -> getEntityManager();
        $con = $me->getConnection();
        $query = $con->prepare("SELECT car_id, order_payment, order_to FROM orders WHERE order_to > CURDATE()");
        $query->execute();
        $result = $query->fetchAll();



        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');

        $cars = $repository->findAll();

        return $this->render('lanoseoBundle:Default:carlist.html.twig', array(

            'cars' => $cars,
            'result' => $result,


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

        $customerRepo = $this ->getDoctrine()->getRepository('lanoseoBundle:Customers');
        $customer = $customerRepo->findAll();
        $selectedCustomer = $customer[0];

        $car = $repository->findBy(array('carId' => $carId ));

        return $this->render('lanoseoBundle:Default:place_order.html.twig', array(

            'car' => $car,
            'customer' => $selectedCustomer,

        ));
    }

    public function rejectPaymentAction(Request $orderRequest)
    {
        $orderId = $orderRequest->get('orderId');
        return $this->render('lanoseoBundle:Default:reject_payment.html.twig', array(

            'orderId' => $orderId,


        ));
    }

    public function successPaymentAction(Request $orderRequest)
    {
        $orderId = $orderRequest->get('orderId');
        return $this->render('lanoseoBundle:Default:success_payment.html.twig', array(

            'orderId' => $orderId,


        ));
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
        $selectedCustomer = $customer[1];

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
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $me = $this -> getDoctrine() -> getEntityManager();
        $con = $me->getConnection();
        $query = $con->prepare("SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
        $query->execute();
        $result = $query->fetchAll();


        //obliczanie ceny

        $diff = $fromDateOrder->diff($toDateOrder);
        $price = 0;
        $discount = 0;
        $percentageDiscount = 0;



        $query = $con->prepare("SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
        $query->execute();
        $result = $query->fetchAll();

        $anotherQuery = $con->prepare("SELECT COUNT(customer_id) FROM orders WHERE order_to BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");
        $anotherQuery->execute();
        $otherResult = $anotherQuery->fetchAll();

        if ($otherResult[0]["COUNT(customer_id)"] >= 3)
        {
            $price = $diff->d * $car[0]->getCarPrice() * 0.8;
            $discount = $diff->d * $car[0]->getCarPrice() * 0.2;
            $percentageDiscount = 20;

        }
        else{
            if ($diff->d > 7)
            {
                $price = $diff->d * $car[0]->getCarPrice() * 0.9;
                $discount = $diff->d * $car[0]->getCarPrice() * 0.1;
                $percentageDiscount = 10;
            }
            else
            {
                $price = $diff->d * $car[0]->getCarPrice();
                $discount = $diff->d * $car[0]->getCarPrice() * 0;
                $percentageDiscount = 0;
            }
        }
        $me = $this -> getDoctrine() -> getEntityManager();
        $con = $me->getConnection();
        $anotherQuery = $con->prepare("UPDATE `orders` SET order_price = ".$price." WHERE order_id=".$result[0]['order_id']);
        $anotherQuery->execute();


        return $this->render('lanoseoBundle:Default:summary.html.twig', array(

            'percentageDiscount' => $percentageDiscount,
            'discount' => $discount,
            'place' => $place,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'car' => $car,
            'result' => $result,
            'price' => $price,
            'customer' => $selectedCustomer,


        ));
    }

    public function paymentAction(Request $orderRequest)
    {
        $orderId = $orderRequest->get('orderId');
        $orderRepository = $this->getDoctrine()->getRepository('lanoseoBundle:Orders');

        $order = $orderRepository->findBy(array('orderId' => $orderId ));

        $count = count($order);

        if($count == 0) {
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/reject_payment/'.$orderId);
        } elseif($order[0]->getOrderPayment() == 1) {
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/reject_payment/'.$orderId);
        } else {

            $paymentParams = array(
                'id' => '725048',
                'amount' => $order[0]->getOrderPrice(),
                'description' => 'Zapłata za wynajem samochodu',
                'firstname' => 'Paweł',
                'lastname' => 'Rachwał',
                'email' => 'jakismail@outlook.com',
                'control' => $orderId,
                'api_version' => 'dev',
                'URL' => 'http://v-ie.uek.krakow.pl/~s182019/app_dev.php/success_payment/' . $orderId,
                'type' => 0,
            );

            $url = sprintf(
                '%s/?%s',
                'https://ssl.dotpay.pl/test_payment/',
                http_build_query($paymentParams)
            );

            return new RedirectResponse($url);
        }
    }

    public function confirmPaymentAction(Request $request)
    {
        $pin = 't5bPAmrw54glSnIGUzGvKi2vaQzWXTfB';

        $sign =
            $pin.
            $request->request->get('id').
            $request->request->get('operation_number').
            $request->request->get('operation_type').
            $request->request->get('operation_status').
            $request->request->get('operation_amount').
            $request->request->get('operation_currency').
            $request->request->get('operation_withdrawal_amount').
            $request->request->get('operation_commission_amount').
            $request->request->get('operation_original_amount').
            $request->request->get('operation_original_currency').
            $request->request->get('operation_datetime').
            $request->request->get('operation_related_number').
            $request->request->get('control').
            $request->request->get('description').
            $request->request->get('email').
            $request->request->get('p_info').
            $request->request->get('p_email').
            $request->request->get('channel').
            $request->request->get('channel_country').
            $request->request->get('geoip_country');

        $signature=hash('sha256', $sign);


        if($signature === $request->request->get('signature')) {
            $me = $this -> getDoctrine() -> getEntityManager();
            $con = $me->getConnection();
            $anotherQuery = $con->prepare("UPDATE `orders` SET order_payment = 1 WHERE order_id=".$request->request->get('control'));
            $anotherQuery->execute();
        }
    }


}
