<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentFee;
use App\Models\StudentClasses;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    //View payments for School Fees
    public function payments()
    {
        //Return all payments made by the current user
        return response()->json(Auth::user()->payments);
    }

    // Pay for School Fees
    public function pay(Request $req){
        //Get the class that the user is in to return the due fees
        $class = StudentClasses::where('class', $req->user()->class)->first();

        if(!$class) return response()->json("Oops sorry, we couldn't determine your current class!", 404);
        //Ceeate a payment transaction
        $payment = StudentFee::create([
            'user_id'=>$req->user()->id,
            'current_class'=>$req->user()->class,
            'fees_due' =>   $class->fees
        ]);

        return response([
            'message'=>'Invoice Generated Successfully!',
            'invoice'=>$payment
        ], 201);        
    }

    // Manual method to mark an invoice as paid
    public function mark_paid($id){

        $fee = StudentFee::where('id', $id)->first();

        //Forbids a user that is not the current user from marking an invoice as paid
        if(Auth::user()->id !== $fee->user_id) return response()->json("You cannot perform this action!", 403);
        //Mark the Invoice as "paid"
        $fee->status = "paid";
        $fee->save();

        return response()->json([
            'message'=>'Fee paid successfully!',
            'data'=>$fee
        ]);

    }

    //Print receipt
    public function  print_receipt($id)
    {
        // Repetition LOL but atleast it works!
        // Forbids a user that is not the current user from printing a receipt.
        $fee = StudentFee::where('id', $id)->first();

        if(Auth::user()->id !== $fee->user_id) return response()->json("You cannot perform this action!", 403);

        return response()->json([
            'name' => $fee->user->firstname." ". $fee->user->lastname,
            'amount' => $fee->fees_due,
            'status' => $fee->status,
            'date' => $fee->created_at
        ]);
    }


}
