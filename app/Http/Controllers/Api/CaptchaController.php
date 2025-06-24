<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
   public function generate()
{
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $answer = $num1 + $num2;

    // You can return the answer in dev/testing
    return response()->json([
        'question' => "What is $num1 + $num2?",
        'captcha_answer' => $answer // for testing
    ]);
}

}
