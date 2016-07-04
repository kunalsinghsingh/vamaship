<?php

namespace Repositories;

interface DashboardInterface {

    public function dashboardInquiryCount($userId, $type, $from, $to, $project, $countType);

    public function getInquiryCountByProject();
    public function getDashboardType();
    
}

?>