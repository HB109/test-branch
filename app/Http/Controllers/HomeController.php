<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::latest()->get();
            return Datatables::of($students)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                                        
                           $btn = '<button id="btnShow" class="btn btn-secondary btn-xs" data-id="'.$row->id.'" onclick="viewFunction('.$row->id.')">View</button>&nbsp;&nbsp;';
                           $btn .= '<a href="'.route('studentEdit', $row->id).'" class="btn btn-info btn-xs">Edit</a>&nbsp;&nbsp;';
                           $btn .= '<a href="'.route('studentDelete', $row->id).'" class="btn btn-danger btn-xs" onclick="return confirm(`Are you sure to delete this record?`)">Delete</a>&nbsp;&nbsp;';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('home');
    }

    public function studentCreate()
    {
        return view('create');
    }

    public function studentFormSave(Request $request){

        $validator = Validator::make($request->all(),[
              'first_name' => 'required|min:3|max:255',
              'last_name'  => 'required|min:3|max:255',
              'phone'      => 'digits:10',
              'date_of_birth'=> 'date|before:2006-03-03',
              'email'      => 'email|unique:students',
              'resume'     => 'mimes:pdf,docx|max:2048',
              'photo'      => 'image|mimes:jpeg,png,jpg|dimensions:min_width=100,min_height=100|max:2048',
        ],[
        'date_of_birth.before' => 'Date of birth should be 18+'
    ]);

        if($validator ->fails()){
            return response()->json(['error'=>$validator->errors()->all()]);
        }else{

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
                $filename =  mt_rand(10000000,99999999).'_.'.$file->getClientOriginalExtension();
                $file->storeAs('public/',$filename);
                $arr['resume'] = $filename;
            }
            if($request->hasFile('photo')){
                $file = $request->file('photo');
                $filename =  mt_rand(10000000,99999999).'_.'.$file->getClientOriginalExtension();
                $file->storeAs('public/',$filename);
                $arr['photo'] = $filename;
            }
            Student::create($arr);
            $msg= 'Student created successfully';
            return response()->json(['success'=>$msg]);
        }
    }

    public function studentDelete($id)
    {
        $students = Student::find($id);        
        if(!empty($students->photo)){
            unlink(public_path('storage/'.$students->photo));
        }
        if(!empty($students->resume)){
            unlink(public_path('storage/'.$students->resume));
        }
        $students = Student::destroy($id);
        return redirect('home')->with('success','Student record is deleted');
    }

    public function studentViewData($id)
    {
        $students = Student::find($id);
        return response()->json(['success'=>$students]);
    }

    public function studentEdit($id)
    {
        $student = Student::find($id);
        return view('edit',compact('student'));
    }

    public function studentUpdate(Request $request,$id)
    {
         $validator = Validator::make($request->all(),[
              'first_name' => 'required|min:3|max:255',
              'last_name'  => 'required|min:3|max:255',
              'phone'      => 'digits:10',
              'date_of_birth'=> 'date|before:2006-03-03',
              'email'      => 'email|unique:students,email,'.$id,
              'resume'     => 'mimes:pdf,docx|max:2048',
              'photo'      => 'image|mimes:jpeg,png,jpg|dimensions:min_width=100,min_height=100|max:2048',
        ],[
        'date_of_birth.before' => 'Date of birth should be 18+'
    ]);

        if($validator ->fails()){
            return response()->json(['error'=>$validator->errors()->all()]);
        }else{
            $students = Student::find($id);   
            $arr = [
                'first_name' => request('first_name'),
                'last_name'  => request('last_name'),                
                'email' => request('email'),
                'phone' => request('phone'),
                'gender' => request('gender'),
                'date_of_birth' => request('date_of_birth')
            ];
            if($request->hasFile('resume')){

                if(!empty($students->resume)){
                    unlink(public_path('storage/'.$students->resume));
                }
                $file = $request->file('resume');
                $filename =  mt_rand(10000000,99999999).'_.'.$file->getClientOriginalExtension();
                $file->storeAs('public/',$filename);
                $arr['resume'] = $filename;
            }
            if($request->hasFile('photo')){
                if(!empty($students->photo)){
                    unlink(public_path('storage/'.$students->photo));
                }
                $file = $request->file('photo');
                $filename =  mt_rand(10000000,99999999).'_.'.$file->getClientOriginalExtension();
                $file->storeAs('public/',$filename);
                $arr['photo'] = $filename;
            }
            Student::where('id',$id)->update($arr);
            $msg= 'Student updated successfully';
            return response()->json(['success'=>$msg]);
        }
    }

    public function resumeDownloadFile($id){
        $path = Student::where("id", $id)->value("resume");        
        $file=storage_path('app/public/'.$path);         
        return Response::download($file);
    }

    
}
