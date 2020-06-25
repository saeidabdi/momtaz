<?php

namespace App\Http\Controllers;

use App\Edu;
use App\Lesson;
use App\Sms;
use App\Stu;
use App\Options;
use Illuminate\Http\Request;
use Verta;
use Log;

class ApiController extends Controller
{
    public function mobile(Request $request)
    {
        $mobile = $request->mobile;
        $stu = Stu::where('mobile', $mobile)->first();
        // شماره وجود داشته
        if ($stu) {
            return response()->json(['status' => $stu->status, 'stu_id' => $stu->id]);
        }
        // شماره وجود نداشته یعنی ثبت نام
        else {
            $new_stu = new Stu;
            $new_stu->mobile = $mobile;
            $new_stu->status = 0;
            $new_stu->time_added = time();
            if ($new_stu->save()) {
                $new_random = new Sms;
                $new_random->stu_id = $new_stu->id;
                $new_random->mobile = $mobile;
                $new_random->code = rand(99999, 1000000);
                $new_random->err = 0;
                $new_random->time_added = time();
                if ($new_random->save()) {
                    // ارسال پیامک به شماره دانش آموز
                    return response()->json(['status' => 0, 'stu_id' => $new_stu->id]);
                }
            }
        }
    }

    public function ok_code(Request $request)
    {
        $random = $request->random;
        $id = $request->stu_id;

        $sms = Sms::where('stu_id', $id)->where('code', $random)->first();
        if ($sms) {
            $stu = Stu::find($id);
            $stu->status = 1;
            if ($stu->update()) {
                return response()->json(['type' => 1, 'status' => 1]);
            }
        } else {
            $student = Sms::where('stu_id', $id)->first();
            if ($student->err < 10) {
                $student->err = $student->err + 1;
                if ($student->update()) {
                    return response()->json(['type' => 0]);
                }
            } else {
                return response()->json(['type' => -2]);
            }
        }
    }

    public function register(Request $request)
    {
        $stu = Stu::where('id', $request->stu_id)->first();

        $stu->name = $request->name;
        $stu->base_id = $request->base;
        if ($request->major == 3) {
            $stu->r_id = null;
        } else {
            $stu->r_id = $request->major;
        }
        $stu->pass = $request->pass;
        $stu->status = 2;

        if ($stu->update()) {
            return response()->json(['status' => 1, 'token' => $stu->mobile . ';' . $stu->id]);
        }
    }

    public function get_home(Request $request)
    {
        $stu = Stu::where('id', explode(';', $request->token)[1])->first();
        if ($stu->status == 2) {
            $message = Options::where('type', 2)->first();
            $logo = '/images/p4.png';
        } else {
            $message = 'پیام مشاور';
            $logo = '';
        }
        $all_img = Options::where('type', 0)->get();


        return response()->json(['slider' => $all_img, 'message' => $message, 'logo' => $logo]);
    }

    public function edu_plan(Request $request)
    {
        $stu = Stu::where('id', explode(';', $request->token)[1])->first();

        $lesson = Lesson::where('base_id', $stu->base_id)->where('r_id', $stu->r_id)->get();
        $n = Verta::createTimestamp(time());


        return response()->json(['lesson' => $lesson, 'day' => $n->formatWord('l'), 'date' => $n->formatJalaliDate()]);
    }

    public function send_edu(Request $request)
    {
        $date_time = floor($request->time);
        $stu_id = explode(';', $request->token)[1];
        $data = json_decode($request->data);
        $today = $request->toDay;
        $clickday = $request->clickDay;

        date_default_timezone_set("Asia/Tehran");
        $h = date('H', time());
        $i = date('i', time());
        $s = date('s', time());
        $bamdad = time() - (($h * 3600) + ($i * 60) + $s);

        if ($today - $clickday == 0) {
            $edu1 = Edu::where('stu_id', $stu_id)->where('date_time', '>', $bamdad)->get();

            Edu::where('stu_id', $stu_id)->where('date_time', '>', $bamdad)->delete();

            foreach ($data as $key => $value) {
                $edu = Edu::insert([
                    'date_time' => $date_time,
                    'l_id' => $data[$key][0],
                    'stu_id' => $stu_id,
                    'study_time' => $data[$key][1],
                    'test_time' => $data[$key][2],
                    'test_count' => $data[$key][3],
                ]);
            }
            return response()->json(['data'=>$data[0],'mes'=>'امروز']);
        } else {
            $edu2 = Edu::where('stu_id', $stu_id)->where('date_time', '<', $bamdad - (86400 * ($today - $clickday - 1)))->where('date_time', '>', $bamdad - (86400 * ($today - $clickday)))->get();

            Edu::where('stu_id', $stu_id)->where('date_time', '<', $bamdad - (86400 * ($today - $clickday - 1)))->where('date_time', '>', $bamdad - (86400 * ($today - $clickday)))->delete();

            foreach ($data as $key => $value) {
                $edu = Edu::insert([
                    'date_time' => $date_time - (86400 * ($today - $clickday)),
                    'l_id' => $data[$key][0],
                    'stu_id' => $stu_id,
                    'study_time' => $data[$key][1],
                    'test_time' => $data[$key][2],
                    'test_count' => $data[$key][3],
                ]);
            }
            return response()->json(['data'=>$data[0],'mes'=>'روز قبل']);
        }
    }

    public function get_edu(Request $request)
    {
        date_default_timezone_set("Asia/Tehran");
        $h = date('H', time());
        $i = date('i', time());
        $s = date('s', time());
        $bamdad = time() - (($h * 3600) + ($i * 60) + $s);

        $today = $request->toDay;
        $clickday = $request->clickDay;
        $stu_id = explode(';', $request->token)[1];

        if ($today - $clickday == 0) {
            $edu = Edu::where('stu_id', $stu_id)->where('date_time', '>', $bamdad)->get();
            if ($edu) {
                return response()->json(['mes' => 'امروز وجود دارد', 'edu' => $edu]);
            }
        } else {
            $edu = Edu::where('stu_id', $stu_id)->where('date_time', '<', $bamdad - (86400 * ($today - $clickday - 1)))->where('date_time', '>', $bamdad - (86400 * ($today - $clickday)))->get();
            if ($edu) {
                return response()->json(['mes' => 'روز قبل وجود دارد', 'edu' => $edu]);
            }
        }

        // return response()->json($today - $clickday);
    }

    public function test()
    {
        // $n = Verta::createTimestamp(time() + 86400);
        // return response()->json($n->formatWord('l'));
        date_default_timezone_set("Asia/Tehran");
        $h = date('H', time());
        $i = date('i', time());
        $s = date('s', time());
        $bamdad = time() - (($h * 3600) + ($i * 60) + $s);
        return response()->json(date('i', $bamdad));
    }
}
