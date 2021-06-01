<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\User;
use App\Rules\MatchOldPassword;
use Hash;
use App\Models\Order;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
class AdminController extends Controller
{
    public function index(){
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
        ->where('created_at', '>', Carbon::today()->subDay(6))
        ->groupBy('day_name','day')
        ->orderBy('day')
        ->get();
     $array[] = ['Name', 'Number'];
     foreach($data as $key => $value)
     {
       $array[++$key] = [$value->day_name, $value->count];
     }
     $trans_by_month_year = Order::all()
        ->groupBy(function ($val) {
            // return Carbon::parse($val->created_at)->format('m');
            return Carbon::parse($val->created_at)->format('m') . ' - ' . Carbon::parse($val->created_at)->format('y');
        });

    $trans_by_year = Order::all()
        ->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('y');
        });
    
    $trans_graph_label = [];
    $trans_graph_count = [];
    foreach ($trans_by_month_year->keys() as $it) {
        array_push($trans_graph_label, $it);
        array_push($trans_graph_count, count($trans_by_month_year[$it]));
    }
    $trans_by_month_year_success = Order::whereIn('status', ['sampai', 'pengiriman'])
        ->get()
        ->groupBy(function ($val) {
            // return Carbon::parse($val->created_at)->format('m');
            return '20' . Carbon::parse($val->created_at)->format('y') . ' ' . Carbon::parse($val->created_at)->format('m');
        });
    $trans_graph_label_success = [];
    $trans_graph_count_success = [];
    foreach ($trans_by_month_year_success->keys()->sort() as $it) {
        array_push($trans_graph_label_success, $it);
        array_push($trans_graph_count_success, count($trans_by_month_year_success[$it]));
    }

    $trans_by_month_year_failed = Order::whereIn('status', ['expired', 'cancel'])
        ->get()
        ->groupBy(function ($val) {
            // return Carbon::parse($val->created_at)->format('m');
            return '20' . Carbon::parse($val->created_at)->format('y') . ' ' . Carbon::parse($val->created_at)->format('m');
        });
    $trans_graph_label_failed = [];
    $trans_graph_count_failed = [];
    foreach ($trans_by_month_year_failed->keys()->sort() as $it) {
        array_push($trans_graph_label_failed, $it);
        array_push($trans_graph_count_failed, count($trans_by_month_year_failed[$it]));
    }

    $trans_graph_label = json_encode($trans_graph_label);
    $trans_graph_count = json_encode($trans_graph_count);
    $trans_graph_label_success = json_encode($trans_graph_label_success);
    $trans_graph_count_success = json_encode($trans_graph_count_success);
    $trans_graph_label_failed = json_encode($trans_graph_label_failed);
    $trans_graph_count_failed = json_encode($trans_graph_count_failed);
    //  return $data;
     return view('backend.index')
     ->with('users', json_encode($array))
     ->with('transbyyear',$trans_by_year)
     ->with('trans_graph_label',$trans_graph_label)
     ->with('trans_graph_count',$trans_graph_count)
     ->with('trans_graph_label_success',$trans_graph_label_success)
     ->with('trans_graph_count_success',$trans_graph_count_success)
     ->with('trans_graph_label_failed',$trans_graph_label_failed)
     ->with('trans_graph_count_failed',$trans_graph_count_failed)
     ->with('trans_by_month_year',$trans_by_month_year);
    }

    public function profile(){
        $profile=Auth()->user();
        // return $profile;
        return view('backend.users.profile')->with('profile',$profile);
    }

    public function profileUpdate(Request $request,$id){
        // return $request->all();
        $user=User::findOrFail($id);
        $data=$request->all();
        $status=$user->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated your profile');
        }
        else{
            request()->session()->flash('error','Please try again!');
        }
        return redirect()->back();
    }

    public function settings(){
        $data=Settings::first();
        return view('backend.setting')->with('data',$data);
    }

    public function settingsUpdate(Request $request){
        // return $request->all();
        $this->validate($request,[
            'short_des'=>'required|string',
            'description'=>'required|string',
            'photo'=>'required',
            'logo'=>'required',
            'address'=>'required|string',
            'email'=>'required|email',
            'phone'=>'required|string',
        ]);
        $data=$request->all();
        // return $data;
        $settings=Settings::first();
        // return $settings;
        $status=$settings->fill($data)->save();
        if($status){
            request()->session()->flash('success','Setting successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again');
        }
        return redirect()->route('admin');
    }

    public function changePassword(){
        return view('backend.layouts.changePassword');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
   
        return redirect()->route('admin')->with('success','Password successfully changed');
    }

    // Pie chart
    public function userPieChart(Request $request){
        // dd($request->all());
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
        ->where('created_at', '>', Carbon::today()->subDay(6))
        ->groupBy('day_name','day')
        ->orderBy('day')
        ->get();
     $array[] = ['Name', 'Number'];
     foreach($data as $key => $value)
     {
       $array[++$key] = [$value->day_name, $value->count];
     }
    //  return $data;
     return view('backend.index')->with('course', json_encode($array));
    }

    // public function activity(){
    //     return Activity::all();
    //     $activity= Activity::all();
    //     return view('backend.layouts.activity')->with('activities',$activity);
    // }
}
