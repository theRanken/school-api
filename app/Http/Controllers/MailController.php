<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\ReceiptMail;
use App\Models\StudentFee;
use Illuminate\Http\Request;

class MailController extends Controller
{
    //
    public function invoice(){
        $invoice = StudentFee::find(1);
        $mail = new InvoiceMail($invoice);
        return  $mail;
    }

    public function receipt(){
        $receipt = StudentFee::find(1);
        $mail = new ReceiptMail($receipt);
        return  $mail;
    }
}
