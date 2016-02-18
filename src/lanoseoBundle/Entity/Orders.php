<?php

namespace lanoseoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders", indexes={@ORM\Index(name="order_id", columns={"order_id"}), @ORM\Index(name="car_id", columns={"car_id"}), @ORM\Index(name="car_id_2", columns={"car_id"}), @ORM\Index(name="car_id_3", columns={"car_id"}), @ORM\Index(name="car_id_4", columns={"car_id"}), @ORM\Index(name="customer_id", columns={"customer_id"})})
 * @ORM\Entity
 */
class Orders
{
    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_date", type="date", nullable=false)
     */
    private $orderDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="order_payment", type="boolean", nullable=false)
     */
    private $orderPayment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_from", type="datetime", nullable=false)
     */
    private $orderFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_to", type="datetime", nullable=false)
     */
    private $orderTo;

    /**
     * @var float
     *
     * @ORM\Column(name="order_price", type="float", precision=10, scale=0, nullable=false)
     */
    private $orderPrice;

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id")
     * })
     */
    private $customer;

    /**
     * @var \Cars
     *
     * @ORM\ManyToOne(targetEntity="Cars")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="car_id", referencedColumnName="car_id")
     * })
     */
    private $car;



    /**
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     * @return Orders
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime 
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set orderPayment
     *
     * @param boolean $orderPayment
     * @return Orders
     */
    public function setOrderPayment($orderPayment)
    {
        $this->orderPayment = $orderPayment;

        return $this;
    }

    /**
     * Get orderPayment
     *
     * @return boolean 
     */
    public function getOrderPayment()
    {
        return $this->orderPayment;
    }

    /**
     * Set orderFrom
     *
     * @param \DateTime $orderFrom
     * @return Orders
     */
    public function setOrderFrom($orderFrom)
    {
        $this->orderFrom = $orderFrom;

        return $this;
    }

    /**
     * Get orderFrom
     *
     * @return \DateTime 
     */
    public function getOrderFrom()
    {
        return $this->orderFrom;
    }

    /**
     * Set orderTo
     *
     * @param \DateTime $orderTo
     * @return Orders
     */
    public function setOrderTo($orderTo)
    {
        $this->orderTo = $orderTo;

        return $this;
    }

    /**
     * Get orderTo
     *
     * @return \DateTime 
     */
    public function getOrderTo()
    {
        return $this->orderTo;
    }

    /**
     * Set orderPrice
     *
     * @param float $orderPrice
     * @return Orders
     */
    public function setOrderPrice($orderPrice)
    {
        $this->orderPrice = $orderPrice;

        return $this;
    }

    /**
     * Get orderPrice
     *
     * @return float 
     */
    public function getOrderPrice()
    {
        return $this->orderPrice;
    }

    /**
     * Set customer
     *
     * @param \lanoseoBundle\Entity\Customers $customer
     * @return Orders
     */
    public function setCustomer(\lanoseoBundle\Entity\Customers $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \lanoseoBundle\Entity\Customers 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set car
     *
     * @param \lanoseoBundle\Entity\Cars $car
     * @return Orders
     */
    public function setCar(\lanoseoBundle\Entity\Cars $car = null)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return \lanoseoBundle\Entity\Cars 
     */
    public function getCar()
    {
        return $this->car;
    }
}
