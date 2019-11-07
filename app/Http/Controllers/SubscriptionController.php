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
        
        //  dd($user->subscription($stripe_name));
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
        
        //  dd($user->subscription($stripe_name));
        $user->subscription($stripe_name)->decrementQuantity();
        }
        return redirect(route('home'))->with('success','sucessfully decreases your subcription to '.$stripe_name." ".'plans');
    }
    public function destroy($id)
    {   
        
        DB::table('subscriptions')
            ->where('stripe_id',$id)
            ->update(['stripe_status' => 'cancelled']);

        \Stripe\Stripe::setApiKey("sk_test_AXKGYcCsUT1KiAOoFtWl9zje00xdpzAtoN");
        $sub = \Stripe\Subscription::retrieve($id);
        $response=$sub->cancel();
       
        return redirect(route('home'))->with('success','sucessfully unsuscribed ');
       
    } 
}
