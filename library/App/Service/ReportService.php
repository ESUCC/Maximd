<?php

class App_Service_ReportService
{
    protected $privCheck;
    protected $reportTitles = array(
        'App_Report_Nssrs' => 'NSSRS'
    );

    function __construct(My_Classes_privCheck $privCheck)
    {
        $this->setPrivCheck($privCheck);
    }

    /**
     * @return mixed
     */
    public function getPrivCheck()
    {
        return $this->privCheck;
    }

    /**
     * @param mixed $privCheck
     */
    public function setPrivCheck($privCheck)
    {
        $this->privCheck = $privCheck;
    }

    /**
     * @return array
     */
    public function getReportTitles()
    {
        return $this->reportTitles;
    }

    /**
     * @param array $reportTitles
     */
    public function setReportTitles($reportTitles)
    {
        $this->reportTitles = $reportTitles;
    }

}
