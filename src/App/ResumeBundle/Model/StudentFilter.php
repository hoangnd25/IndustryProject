<?php

namespace App\ResumeBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class StudentFilter
{
    protected $keyword;
    protected $industry;
    protected $gs1Certification;
    protected $employmentStatus;
    protected $country;
    protected $institution;
    protected $workingRight;
    protected $availableFrom;

    /**
     * @return mixed
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * @param mixed $industry
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;
    }

    /**
     * @return mixed
     */
    public function getGs1Certification()
    {
        return $this->gs1Certification;
    }

    /**
     * @param mixed $gs1Certification
     */
    public function setGs1Certification($gs1Certification)
    {
        $this->gs1Certification = $gs1Certification;
    }

    /**
     * @return mixed
     */
    public function getEmploymentStatus()
    {
        return $this->employmentStatus;
    }

    /**
     * @param mixed $employmentStatus
     */
    public function setEmploymentStatus($employmentStatus)
    {
        $this->employmentStatus = $employmentStatus;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param mixed $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * @return mixed
     */
    public function hasWorkingRight()
    {
        return $this->workingRight;
    }

    /**
     * @param mixed $workingRight
     */
    public function setWorkingRight($workingRight)
    {
        $this->workingRight = filter_var($workingRight, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return mixed
     */
    public function getAvailableFrom()
    {
        return $this->availableFrom;
    }

    /**
     * @param mixed $availableFrom
     */
    public function setAvailableFrom($availableFrom)
    {
        $this->availableFrom = $availableFrom;
    }

    public function isEmpty(){
        return
            !(
                count($this->industry) > 0 ||
                count($this->country) > 0 ||
                count($this->employmentStatus) > 0 ||
                $this->workingRight !== null ||
                $this->availableFrom !== null ||
                count($this->gs1Certification) > 0 ||
                count($this->institution) > 0
            );
    }
}