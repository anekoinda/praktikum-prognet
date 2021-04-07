<?php
 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 // Panggil SendMail yang telah dibuat
 use App\Mail\SendMail;
 // Panggil support email dari Laravel
 use Illuminate\Support\Facades\Mail;
 class EmailController extends Controller
 {
     public function index()
     {
         $nama = "Wildan Fuady";
         $email = "wildanfuady@gmail.com";
         $kirim = Mail::to($email)->send(new SendMail($nama));
     if($kirim){         echo "Email telah dikirim";     } }
 }