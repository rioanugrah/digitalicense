<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NoReplyEmail;
use Illuminate\Support\Facades\Mail;

class TestingController extends Controller
{
    public function testing_email()
    {
        Mail::to('rioanugrah8899@gmail.com')->send(new NoReplyEmail('Rio Anugrah','rioanugrah8899@gmail.com',300000));
        return 'Email Terkirim';
    }
}
