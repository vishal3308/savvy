<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function redirecttohome(){
        $data=['name'=>"Vishal Maurya",'email'=>"vishalmaurya3308@gmail.com",'status'=>1];
        return redirect()->route('testresult',$data);
    }
}
