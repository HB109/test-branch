<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class studentController extends Controller
{

    public function index(){
        $students = Student::get();
        return view('home', compact('students'));
    }
    public function studentFormSave(Request $request){

        $arr = [
                'first_name' => request('first_name'),
                'last_name'  => request('last_name'),                
                'email' => request('email'),
                'phone' => request('phone'),
                'gender' => request('gender'),
                'date_of_birth' => request('date_of_birth')
            ];
            if($request->hasFile('resume')){
                $file = $request->file('resume');
                $filename = $file->getClientOriginalName();
                $file->storeAs('public/',$filename);
                // return redirect('/uploadfile');
                $arr['resume'] = $filename;
            }
            if($request->hasFile('photo')){
                $file = $request->file('photo');
                $filename = $file->getClientOriginalName();
                $file->storeAs('public/',$filename);
                // return redirect('/uploadfile');
                $arr['photo'] = $filename;
            }
            Student::create($arr);
            $msg= 'Student created successfully';
        return response()->json(['res'=>$msg]);
    }
}
