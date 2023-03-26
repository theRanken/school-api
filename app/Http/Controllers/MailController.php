<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\StudentFee;
use Illuminate\Http\Request;

class MailController extends Controller
{
    //
    public function invoice(Request $req){
        $invoice = StudentFee::find(1);
        $mail = new InvoiceMail($invoice);
        return  $mail;
    }
}
