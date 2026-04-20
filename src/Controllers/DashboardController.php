<?php

namespace Agenda\Controllers;
use Agenda\Core\Pages;
class DashboardController
{
    public function __construct(){
        $this->pages =new Pages();
    }

    public function index():void{
        $this->pages->render("dashboard/index");
    }
}