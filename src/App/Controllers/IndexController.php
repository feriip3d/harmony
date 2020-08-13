<?php
namespace App\Controllers;

use Harmony\Resources\Database;
use Harmony\Resources\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return $this->render("index");
    }
}