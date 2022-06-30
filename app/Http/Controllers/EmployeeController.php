<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response; 
use Illuminate\Support\Facades\Validator;
use File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index()
     {
        $result['data'] = Employee::all();
        return view('index', $result);
     }
    
    public function EmployeeForm($slug){
        $result = [];
        if ($slug != 'add') {
            $arr = Employee::where('email', $slug)->first();
            $result['first_name'] = $arr->first_name;
            $result['last_name'] = $arr->last_name;
            $result['email'] = $arr->email;
            $result['phone'] = $arr->phone;
            $result['gender'] = $arr->gender;
            $result['image'] = $arr->image;
            $result['id'] = $arr->id;
        } else {
            $result['first_name'] = '';
            $result['last_name'] = '';
            $result['email'] = '';
            $result['phone'] = '';
            $result['gender'] = '';
            $result['image'] = '';
            $result['id'] = 0;
        }
        return view('manage', $result);
    }

    public function EmployeeManage(Request $request)
    {
        if ($request->id > 0) {
            $image_validation = 'mimes:jpg,png,jpeg,gif,svg|max:2048';
        } else {
            $image_validation = 'required|mimes:jpg,png,jpeg,gif,svg|max:2048';
        }       
        $request->validate([
            'first_name'=> 'required',
            'last_name'=>'required',
            'email' => 'required|unique:employees,email,'.$request->id,
            'phone' => 'required|unique:employees,phone,'.$request->id,
            'gender'=>'required',
            'image' => $image_validation,
        ]);
        
            if ($request->id > 0) {
                $model = Employee::find($request->id);

                $message = "Employee Updated";
                if($request->hasfile('image')){
                    $path = 'assets/images/employee/'.$model->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                }
            } else {
                $model = new Employee();
                $message = "Employee Added";
            }
            
                if($request->hasfile('image'))
                {
                    $filenameWithExt = $request->file('image')->getClientOriginalName();
                    $filename  = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $request->image->move(public_path('assets/images/employee'), $fileNameToStore);
                    $model->image = $fileNameToStore;
                }
            $model->first_name = $request->first_name;
            $model->last_name = $request->last_name;
            $model->email = $request->email;
            $model->phone = $request->phone;
            $model->gender = $request->gender;
            $model->save();
            return redirect('/')->with('success_msg', $message);
    }


    public function EmployeeDelete($id)
    {
        $emp = Employee::find($id);

        $path = 'assets/images/employee/'.$emp->image;
        if(File::exists($path))
        {
            File::delete($path);
        }
        $emp->delete(); 
        return response()->json([
            'status' => 200,
            'message' => 'Employee deleted',
        ]);
    }

    public function EmployeeStatus($status, $email)
    {
        if ($status == "deactive") {
            $emp_status = '0';
        } elseif($status == "active") {
            $emp_status = '1';
        }
        $model = Employee::where('email', $email)->first();
        if ($model != null) {
            $model->status = $emp_status;
            $model->save();
            return redirect('/');
        }
    }

    
    public function EmployeeView($id)
    {
        return response()->json([
            'status' => 200,
            'message' => 'Employee Data',
            'data' => Employee::find($id),
        ]);
    }

}
