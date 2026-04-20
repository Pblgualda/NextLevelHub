<?php

namespace NextLevelHub\Controllers;
use NextLevelHub\Core\Pages;
class DashboardController
{
    public function __construct(){
        $this->pages =new Pages();
    }

    public function index():void{
        $this->pages->render("dashboard/index");
    }
}