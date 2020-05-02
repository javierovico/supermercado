<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThoughtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return [];
    }

    public function store(Request $request)
    {
        return [];
    }

    public function update(Request $request, $id)
    {
        return [];
    }

    public function destroy($id)
    {

    }
}
