<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\plans;
use Auth;
class SubscriptionController extends Controller
{
    //

    public function index($id)
    {
        
        $subscription = DB::table('subscriptions')->where([['user_id',$id],['stripe_status','active']])->get();
        return view('subscription.index', compact('subscription'));
    } 

    public function increase($id)
    {
        $subscription = DB::table('subscriptions')->where([['stripe_id',$id],['stripe_status','active']])->get();
        foreach($subscription as $sub)
            {
                $stripe_plan=$sub->stripe_plan;
                $plans=DB::table('plans')->where('stripe_plan',$stripe_plan)->first();
                $stripe_name= $plans->name;
                $user =User::find(Auth::user()->id);
                $user->subscription($stripe_name)->incrementQuantity();
            }
        return redirect(route('subscription.index',$user->id))->with('success','sucessfully increases your subcription to '.$stripe_name." ".'plans');
    }

    public function decrease($id)
    {
        $subscription = DB::table('subscriptions')->where([['stripe_id',$id],['stripe_status','active']])->get();
        foreach($subscription as $sub)
            {
                $stripe_plan=$sub->stripe_plan;
                $plans=DB::table('plans')->where('stripe_plan',$stripe_plan)->first();
                $stripe_name= $plans->name;
                $user =User::find(Auth::user()->id);
                $user->subscription($stripe_name)->decrementQuantity();
            }
        return redirect(route('subscription.index',$user->id))->with('success','sucessfully decreases your subcription to '.$stripe_name." ".'plans');
    }

    public function destroy($id)
    {   
        $user =User::find(Auth::user()->id);
        $subscription=DB::table('subscriptions')
        ->where([['stripe_id',$id],['user_id',$user->id]])->first();
        $user->subscription($subscription->name)->cancelNow();
        return redirect(route('subscription.index',$user->id))->with('success','sucessfully unsuscribed ');
       
    } 
    public function show($id)
    {
        $plans = plans::all();
        $user_id=Auth::user()->id;
        $subscription = DB::table('subscriptions')->where([['user_id',$user_id],['stripe_status','active']])->get();
        // dd($subscription);
        return view('subscription.change_subscription',compact('subscription','plans','id')); 
    }
    public function change(Request $request,$id)
    {  
       
        $user_id=Auth::user()->id;
        $user =User::find(Auth::user()->id);
        $stripe_plan=$request->stripe_plan;
        $subscription = DB::table('subscriptions')->where([['stripe_plan',$stripe_plan],['user_id',$user_id],['stripe_status','active']])->first();
        $plans=DB::table('plans')->where('stripe_plan',$id)->first();
        $stripe_names=$plans->name;
        $stripe_name=$subscription->name;
        $user->subscription($stripe_name)->swap($id)->update(['name' => $stripe_names]);
   
        return redirect(route('subscription.index',$user_id))->with('success','sucessfully subscription changed ');
        
    }

}
