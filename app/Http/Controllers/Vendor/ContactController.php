<?php

namespace App\Http\Controllers\vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Content;

class ContactController extends Controller
{
    public function index()
    {
        $data = Content::where('slug','contact-us')->first();
        return view('vendor.contact.index', compact('data'));
    }
}
