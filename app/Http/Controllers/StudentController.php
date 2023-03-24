<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentFee;
use App\Models\StudentClasses;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    //View payments for School Fees
    public function payments(Request $req)
    {
        $user = $req->user()->id;
        $payments = StudentFee::where('user_id', $user);

        return "This is the payments page";
    }

    // Pay for School Fees
    public function pay(Request $req){
        $class = StudentClasses::where('class', $req->user()->class)->first();

        if(!$class) return response("We couldn't find the student's class you are looking for!", 404);

        $payment = StudentFee::create([
            'user_id'=>Auth::user()->id,
            'current_class'=>Auth::user()->class,
            'fees_due' =>   $class->fees
        ]);

        return response([
            'message'=>'Invoice Generated Successfully!',
            'invoice'=>$payment
        ], 201);
    }

    // Manual method to mark an invoice as paid
    public function mark_paid(Request $req, StudentFee $fee){

    }

    //Print receipt
    public function  print_receipt(Request $req, StudentFee $fee)
    {

    }


}
