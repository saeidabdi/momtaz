<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Lesson;
use App\Mosh;
use App\Options;
use App\Planing;
use App\Stu;
use Illuminate\Http\Request;
use DB;
use Session;

class AdminController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function login_admin(Request $request)
    {
        $username = $request->username;
        $pass = $request->pass;

        $logined = Admin::where('username', $username)->where('pass', $pass)->first();

        if ($logined) {
            Session::put('username', $username);
            return $logined;
        }
    }

    public function dashbord()
    {
        return view('admin.dashbord');
    }

    public function getuser()
    {
        $username = Session::get('username');
        $user = Admin::where('username', $username)->first();
        return $user;
    }

    public function slider()
    {
        return view('admin.slider');
    }

    public function exit_admin()
    {
        $exit_user = Session::forget('username');
        if ($exit_user) {
            return true;
        }
    }

    // option
    public function formSubmit(Request $request)
    {
        // ini_set("memory_limit", "500000");
        // ini_set('post_max_size', '500000');
        // ini_set('upload_max_filesize', '500000');
        // $validator = Validator::make($request->all(), [
        //     'file' => 'max:500000', //5MB 
        // ]);
        $imageName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('images'), $imageName);

        $options = new Options;
        $options->type = 0;
        $options->vlaue = $imageName;

        if ($options->save()) {
            return response()->json($imageName);
        }
    }

    public function get_imag_slide()
    {
        $all_img = Options::where('type', 0)->get();

        return response()->json($all_img);
    }

    public function slider_img(Request $request)
    {
        if (Options::where('id', $request->id)->delete()) {
            return response()->json(['mes' => 'عکس اسلایدر حذف شد']);
        }
    }

    public function get_message()
    {
        $all_img = Options::where('type', 2)->first();

        return response()->json($all_img);
    }

    public function edit_message(Request $request)
    {
        Options::where('type', 2)->update([
            'vlaue' => $request->message
        ]);
    }

    // student
    public function stu()
    {
        return view('admin.stu');
    }

    public function get_stu()
    {
        $stu = DB::table('stu')
            ->orderby('id', 'desc')
            ->limit(100)
            // ->leftJoin('branch', 'teacher_management.b_id', '=', 'branch.id')
            ->get();
        return $stu;
    }

    public function search_stu(Request $request)
    {
        $stu = DB::table('stu')
            ->where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $stu;
    }

    // lesson
    public function lesson()
    {
        return view('admin.lesson');
    }

    public function add_lesson(Request $request)
    {
        $id = $request->id;
        if ($id) {
            // $new_lesson = Lesson::where('id', $id)->first();
            // $new_lesson->name = $request->name;
            // if ($request->status) {
            //     $new_lesson->status = $request->status;
            // } else {
            //     $new_lesson->status = 0;
            // }
            // $new_lesson->p_id = $request->paye_id;
            // $new_lesson->r_id = $request->reshte_id;

            // if ($new_lesson->update()) {
            //     return response()->json(['mes' => 'درس بروزرسانی شد']);
            // }
        } else {
            $new_lesson = new Lesson;
            $new_lesson->title = $request->name;
            $new_lesson->base_id = $request->paye_id;
            $new_lesson->r_id = $request->reshte_id;

            if ($new_lesson->save()) {
                return response()->json(['mes' => 'درس جدید ایجاد شد']);
            }
        }
    }

    public function get_lesson(Request $request)
    {
        $all_lesson = Lesson::where('base_id', $request->p_id)->where('r_id', $request->r_id)->get();

        return response()->json($all_lesson);
    }

    // mosh
    public function mosh()
    {
        return view('admin.mosh');
    }

    public function get_mosh()
    {
        $mosh = DB::table('mosh')
            ->orderby('id', 'desc')
            ->limit(100)
            ->get();
        return $mosh;
    }

    public function search_mosh(Request $request)
    {
        $stu = DB::table('mosh')
            ->where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $stu;
    }

    public function unactive_mosh(Request $request)
    {
        $mosh = Mosh::where('id', $request->id)->update([
            'status' => -1
        ]);
        if ($mosh) {
            return $mosh;
        }
    }

    // plan
    public function plan()
    {
        return view('admin.plan');
    }

    public function formimgplan(Request $request)
    {
        // ini_set("memory_limit", "500000");
        // ini_set('post_max_size', '500000');
        // ini_set('upload_max_filesize', '500000');
        // $validator = Validator::make($request->all(), [
        //     'file' => 'max:500000', //5MB 
        // ]);
        $imageName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('images'), $imageName);

        return response()->json($imageName);
    }

    public function add_plan(Request $request)
    {
        if ($request->id) {
            $new_plan = Planing::where('id',$request->id)->first();
            $new_plan->title = $request->title;
            $new_plan->parent = $request->parent;
            $new_plan->is_ready = $request->is_ready;
            $new_plan->price = $request->price;
            $new_plan->img = $request->img;
            $new_plan->is_end = $request->is_end;
            $new_plan->is_exam = $request->plan_isexam;

            if ($new_plan->update()) {
                DB::table('plan_exam')->where('planing_id',$request->id)->update([
                    'file' => $request->file_addr,
                ]);
                return response()->json(['id' => $new_plan->id,'mes'=>'برنامه بروزرسانی شد']);
            }
        } else {
            $new_plan = new Planing;
            $new_plan->title = $request->title;
            $new_plan->parent = $request->parent;
            $new_plan->is_ready = $request->is_ready;
            $new_plan->price = $request->price;
            $new_plan->img = $request->img;
            $new_plan->is_end = $request->is_end;
            $new_plan->is_exam = $request->plan_isexam;

            if ($new_plan->save()) {
                DB::table('plan_exam')->insert([
                    'planing'=> $new_plan->id,
                    'file' => $request->file_addr,
                ]);
                return response()->json(['id' => $new_plan->id,'mes'=>'برنامه ایجاد شد']);
            }
        }
    }

    public function get_plan()
    {
        // $all_img = Planing::all();
        $all_planing = 
        DB::table('planing')
        ->leftJoin('plan_exam', 'planing.id', '=', 'plan_exam.planing_id')
        ->select('planing.*','plan_exam.file')
        ->get();

        return response()->json($all_planing);
    }

    public function delete_plan(Request $request)
    {
        if (Planing::where('id', $request->id)->delete()) {
            return response()->json('با موفقیت حذف شد');
        }
    }
}
