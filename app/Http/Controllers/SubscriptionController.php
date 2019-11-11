<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
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
        return redirect(route('home'))->with('success','sucessfully increases your subcription to '.$stripe_name." ".'plans');
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
        return redirect(route('home'))->with('success','sucessfully decreases your subcription to '.$stripe_name." ".'plans');
    }
    public function destroy($id)
    {   
        $user =User::find(Auth::user()->id);
        $subscription=DB::table('subscriptions')
                        ->where([['stripe_id',$id],['user_id',$user->id]])->first();
        $user->subscription($subscription->name)->cancelNow();
        return redirect(route('home'))->with('success','sucessfully unsuscribed ');
       
    } 
}
