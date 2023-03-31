<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\ReceiptMail;
use App\Mail\NotificationMail;
use Illuminate\Http\Request;
use App\Models\StudentFee;
use App\Models\StudentClasses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Notifications\StudentNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Console\Output\ConsoleOutput;

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

        Mail::to($payment->user->email)->send(new InvoiceMail($payment));
        
        return response()->json([
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


        // Send Generated receipt
        Mail::to($fee->user->email)->send(new ReceiptMail($fee));

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

        if(!$fee) return response()->json("You Have No Receipts!", 404);
        if(Auth::user()->id !== $fee->user_id) return response()->json("You cannot perform this action!", 403);

        return response()->json([
            'name' => $fee->user->firstname." ". $fee->user->lastname,
            'amount' => $fee->fees_due,
            'status' => $fee->status,
            'date' => $fee->created_at
        ]);
    }

    public function notify(Request $req){
        $req->validate([
            'subject' => 'string|required',
            'body' => 'string|required'
        ]);

        $users = User::all('firstname', 'lastname', 'email');


        // instantiate a logger
         $logger = new ConsoleOutput();

        // Send Email wih request data
        try{

            echo Notification::send($users, new StudentNotification($req->subject, $req->body)); 

            return response()->json([
                'message' => "Emails Dispatched Successfully!"
            ]);
        }catch(Exception $e){
        //    log errors to console
            $logger->writeln("Error: ".$e->getMessage()." in ".$e->getFile()." on Line: :".$e->getLine());
            return response()->json([
                'message' => "Emails failed to dispatch!"
            ], 500);
        }
    }


}
