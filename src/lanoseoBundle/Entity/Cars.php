<?php

namespace lanoseoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cars
 *
 * @ORM\Table(name="cars", indexes={@ORM\Index(name="car_id", columns={"car_id"})})
 * @ORM\Entity
 */
class Cars
{
    /**
     * @var integer
     *
     * @ORM\Column(name="car_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $carId;

    /**
     * @var string
     *
     * @ORM\Column(name="car_name", type="text", length=65535, nullable=false)
     */
    private $carName;

    /**
     * @var string
     *
     * @ORM\Column(name="car_segment", type="string", length=5, nullable=false)
     */
    private $carSegment;

    /**
     * @var float
     *
     * @ORM\Column(name="car_price", type="float", precision=10, scale=0, nullable=false)
     */
    private $carPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="car_image", type="text", length=65535, nullable=false)
     */
    private $carImage;



    /**
     * Get carId
     *
     * @return integer 
     */
    public function getCarId()
    {
        return $this->carId;
    }

    /**
     * Set carName
     *
     * @param string $carName
     * @return Cars
     */
    public function setCarName($carName)
    {
        $this->carName = $carName;

        return $this;
    }

    /**
     * Get carName
     *
     * @return string 
     */
    public function getCarName()
    {
        return $this->carName;
    }

    /**
     * Set carSegment
     *
     * @param string $carSegment
     * @return Cars
     */
    public function setCarSegment($carSegment)
    {
        $this->carSegment = $carSegment;

        return $this;
    }

    /**
     * Get carSegment
     *
     * @return string 
     */
    public function getCarSegment()
    {
        return $this->carSegment;
    }

    /**
     * Set carPrice
     *
     * @param float $carPrice
     * @return Cars
     */
    public function setCarPrice($carPrice)
    {
        $this->carPrice = $carPrice;

        return $this;
    }

    /**
     * Get carPrice
     *
     * @return float 
     */
    public function getCarPrice()
    {
        return $this->carPrice;
    }

    /**
     * Set carImage
     *
     * @param string $carImage
     * @return Cars
     */
    public function setCarImage($carImage)
    {
        $this->carImage = $carImage;

        return $this;
    }

    /**
     * Get carImage
     *
     * @return string 
     */
    public function getCarImage()
    {
        return $this->carImage;
    }
}
