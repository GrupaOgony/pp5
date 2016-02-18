<?php

namespace lanoseoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customers
 *
 * @ORM\Table(name="customers", indexes={@ORM\Index(name="customer_id", columns={"customer_id"})})
 * @ORM\Entity
 */
class Customers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="customer_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $customerId;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_name", type="string", length=50, nullable=false)
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_surname", type="string", length=50, nullable=false)
     */
    private $customerSurname;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_email", type="string", length=100, nullable=false)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_password", type="text", length=65535, nullable=false)
     */
    private $customerPassword;



    /**
     * Get customerId
     *
     * @return integer 
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     * @return Customers
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName
     *
     * @return string 
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set customerSurname
     *
     * @param string $customerSurname
     * @return Customers
     */
    public function setCustomerSurname($customerSurname)
    {
        $this->customerSurname = $customerSurname;

        return $this;
    }

    /**
     * Get customerSurname
     *
     * @return string 
     */
    public function getCustomerSurname()
    {
        return $this->customerSurname;
    }

    /**
     * Set customerEmail
     *
     * @param string $customerEmail
     * @return Customers
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string 
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Set customerPassword
     *
     * @param string $customerPassword
     * @return Customers
     */
    public function setCustomerPassword($customerPassword)
    {
        $this->customerPassword = $customerPassword;

        return $this;
    }

    /**
     * Get customerPassword
     *
     * @return string 
     */
    public function getCustomerPassword()
    {
        return $this->customerPassword;
    }
}
