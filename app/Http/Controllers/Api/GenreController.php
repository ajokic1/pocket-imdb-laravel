<?php

namespace App\Http\Controllers\Api;

use App\Genre;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Genre::all();
    }
}
