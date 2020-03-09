<?php


namespace App\Objects;


class PropertiesPowerpoint
{

    private $nbDansesSlides;

    private $primaryDanseColor;

    private $secondaryDanseColor;

    private $backgroundSlides;

    /**
     * @return mixed
     */
    public function getBackgroundSlides()
    {
        return $this->backgroundSlides;
    }

    /**
     * @param mixed $backgroundSlides
     * @return PropertiesPowerpoint
     */
    public function setBackgroundSlides($backgroundSlides)
    {
        $this->backgroundSlides = $backgroundSlides;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbDansesSlides()
    {
        return $this->nbDansesSlides;
    }

    /**
     * @param mixed $nbDansesSlides
     * @return PropertiesPowerpoint
     */
    public function setNbDansesSlides($nbDansesSlides)
    {
        $this->nbDansesSlides = $nbDansesSlides;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryDanseColor()
    {
        return $this->primaryDanseColor;
    }

    /**
     * @param mixed $primaryDanseColor
     * @return PropertiesPowerpoint
     */
    public function setPrimaryDanseColor($primaryDanseColor)
    {
        $this->primaryDanseColor = $primaryDanseColor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecondaryDanseColor()
    {
        return $this->secondaryDanseColor;
    }

    /**
     * @param mixed $secondaryDanseColor
     * @return PropertiesPowerpoint
     */
    public function setSecondaryDanseColor($secondaryDanseColor)
    {
        $this->secondaryDanseColor = $secondaryDanseColor;
        return $this;
    }



}