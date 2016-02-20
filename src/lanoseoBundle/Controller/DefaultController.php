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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        if($session->get('zalogowany')) {

            $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');
            $customer = $repository->findBy(array('customerId' => $session->get('zalogowany')));
            $loginName = $customer[0]->getCustomerName()." ".$customer[0]->getCustomerSurname();
        } else {

            $loginName = "";
        }
        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');
        $mostlyOrderedCars = $this->getDoctrine()->getRepository('lanoseoBundle:Orders');
        $cars = $repository->findAll();
        $orders = $mostlyOrderedCars->findAll();




        return $this->render('lanoseoBundle:Default:index.html.twig', array(

            'orders' => $orders,
            'cars' => $cars,

            'loginName' => $loginName,

        ));
    }
    public function contactAction(Request $request)
    {
        $session = $request->getSession();
        if($session->get('zalogowany')) {

            $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');
            $customer = $repository->findBy(array('customerId' => $session->get('zalogowany')));
            $loginName = $customer[0]->getCustomerName()." ".$customer[0]->getCustomerSurname();
        } else {

            $loginName = "";
        }
        return $this->render('lanoseoBundle:Default:contact.html.twig', array(


            'loginName' => $loginName,

        ));
    }

    public function loginAction(Request $loginData)
    {
        $session = $loginData->getSession();
        $customerEmail = $loginData->get('customerEmail');
        $customerPassword = $loginData->get('customerPassword');

        if($session->get('zalogowany')) {
            $loginCheck = 3;
            //unset($session->get('zalogowany'));
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/');
        } else {


            $loginCheck = 0;

            if (isset($_POST['signin'])) {
                if (empty($_POST['customerEmail']) || empty($_POST['customerPassword'])) {
                    $loginCheck = 1;
                } else {
                    $customerRepository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');
                    $customer = $customerRepository->findBy(array('customerEmail' => $customerEmail));
                    if (count($customer) == 1) {
                        if ($customer[0]->getCustomerPassword() == sha1($customerPassword)) {
                            //$session->get('zalogowany') = $customer[0]->getCustomerId();
                            $session->set('zalogowany', $customer[0]->getCustomerId());
                            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/');
                        } else {
                            $loginCheck = 2;
                        }
                    } else {
                        $loginCheck = 2;
                    }
                }
            }
        }

        return $this->render('lanoseoBundle:Default:login.html.twig', array(

            'customerEmail' => $customerEmail,
            'loginCheck' => $loginCheck,

        ));
    }

    public function registerAction(Request $registerData)
    {
        $session = $registerData->getSession();
        $customerName = $registerData->get('customerName');
        $customerSurname = $registerData->get('customerSurname');
        $customerEmail = $registerData->get('customerEmail');
        $customerPassword = $registerData->get('customerPassword');
        if($session->get('zalogowany')) {
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/');
        } else {
            // 0 - maila nie ma w bazie danych, 1 - mail jest w bazie danych, 2 - rejestracja ok, 3 - wypełnij pola
            $registerCheck = 0;

            $customerRepository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');

            $customer = $customerRepository->findBy(array('customerEmail' => $customerEmail));


            if (count($customer) == 1) {
                $registerCheck = 1;
            } else {
                if (isset($_POST['signup'])) {
                    if (empty($_POST['customerEmail']) || empty($_POST['customerName']) || empty($_POST['customerSurname']) || empty($_POST['customerPassword'])) {
                        $registerCheck = 3;
                    } else {
                        $registerCheck = 2;
                        $customer = new Customers();
                        $customer->setCustomerName($customerName);
                        $customer->setCustomerSurname($customerSurname);
                        $customer->setCustomerEmail($customerEmail);
                        $customer->setCustomerPassword(sha1($customerPassword));
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($customer);
                        $em->flush();
                    }

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
    public function carlistAction(Request $request)
    {
        $session = $request->getSession();
        if($session->get('zalogowany')) {

            $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');
            $customer = $repository->findBy(array('customerId' => $session->get('zalogowany')));
            $loginName = $customer[0]->getCustomerName()." ".$customer[0]->getCustomerSurname();
            $userId = $session->get('zalogowany');
        } else {

            $loginName = "";
            $userId = "";
        }
        $for = $request->get('for');
        $how = $request->get('how');
        $nowDate = new \DateTime();
        $me = $this -> getDoctrine() -> getEntityManager();
        $con = $me->getConnection();
        $query = $con->prepare("SELECT car_id, customer_id, order_id, order_payment, order_to FROM orders WHERE order_to > CURDATE()");
        $query->execute();
        $result = $query->fetchAll();



        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');
        if($for == "price" && $how == "asc") {
            $cars = $repository->findBy(array(),array('carPrice' => 'asc'));
        } elseif($for == "name" && $how == "asc") {
            $cars = $repository->findBy(array(),array('carName' => 'asc'));
        } elseif($for == "segment" && $how == "asc") {
            $cars = $repository->findBy(array(),array('carSegment' => 'asc'));
        } elseif($for == "name" && $how == "desc") {
            $cars = $repository->findBy(array(),array('carName' => 'desc'));
        } elseif($for == "segment" && $how == "desc") {
            $cars = $repository->findBy(array(),array('carSegment' => 'desc'));
        } elseif($for == "price" && $how == "desc") {
            $cars = $repository->findBy(array(),array('carSegment' => 'desc'));
        } else {
            $cars = $repository->findBy(array(),array('carPrice' => 'asc'));
        }



        return $this->render('lanoseoBundle:Default:carlist.html.twig', array(

            'cars' => $cars,
            'result' => $result,
            'userId' => $userId,
            'for' => $for,
            'how' => $how,
            'loginName' => $loginName,

        ));
    }

    public function errorAction()
    {
        return $this->render('lanoseoBundle:Default:error.html.twig');
    }

    public function placeOrderAction(Request $carRequest)
    {
        $session = $carRequest->getSession();
        if($session->get('zalogowany')) {

            $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');
            $customer = $repository->findBy(array('customerId' => $session->get('zalogowany')));
            $loginName = $customer[0]->getCustomerName()." ".$customer[0]->getCustomerSurname();

        $carId = $carRequest->get('carId');
        $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Cars');


        $car = $repository->findBy(array('carId' => $carId ));
        } else {

            $loginName = "";
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/login');
        }

        return $this->render('lanoseoBundle:Default:place_order.html.twig', array(

            'car' => $car,

            'loginName' => $loginName,

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
        $session = $orderRequest->getSession();
        if($session->get('zalogowany')) {

            $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');
            $customer = $repository->findBy(array('customerId' => $session->get('zalogowany')));
            $loginName = $customer[0]->getCustomerName()." ".$customer[0]->getCustomerSurname();
        } else {

            $loginName = "";
        }
        $orderId = $orderRequest->get('orderId');
        return $this->render('lanoseoBundle:Default:success_payment.html.twig', array(

            'orderId' => $orderId,

            'loginName' => $loginName,

        ));
    }

    public function summaryAction(Request $request)
    {
        $session = $request->getSession();
        if($session->get('zalogowany')) {

            $repository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');
            $customer = $repository->findBy(array('customerId' => $session->get('zalogowany')));
            $loginName = $customer[0]->getCustomerName()." ".$customer[0]->getCustomerSurname();


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
        } else {

            $loginName = "";
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/');
        }

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
            'loginName' => $loginName,



        ));
    }

    public function paymentAction(Request $orderRequest)
    {
        $orderId = $orderRequest->get('orderId');
        $orderRepository = $this->getDoctrine()->getRepository('lanoseoBundle:Orders');

        $order = $orderRepository->findBy(array('orderId' => $orderId ));

        $customerRepository = $this->getDoctrine()->getRepository('lanoseoBundle:Customers');

        $customer = $customerRepository->findBy(array('customerId' => $order[0]->getCustomer() ));

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
                'firstname' => $customer[0]->getCustomerName(),
                'lastname' => $customer[0]->getCustomerSurname(),
                'email' => $customer[0]->getCustomerEmail(),
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

    public function logoutAction(Request $request)
    {
        $session = $request->getSession();

        if($session->get('zalogowany')) {
            $session->remove('zalogowany');
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/');
        } else {
            return new RedirectResponse('http://v-ie.uek.krakow.pl/~s182019/app_dev.php/');
        }
    }

}
