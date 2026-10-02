<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{
    /**
     * Page d'accueil du front pour les utilisateurs connectés.
     */
    public function index()
    {
        return view('front.home');
    }
}
