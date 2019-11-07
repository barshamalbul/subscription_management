<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
Use App\User;
Use App\plans;

class plansController extends Controller
{
    public function index()
    {
        $plans = plans::all();
        $user_id=Auth::user()->id;
        $subscription = DB::table('subscriptions')->where([['user_id',$user_id],['stripe_status','active']])->get();

        return view('plans.index', compact('plans','subscription'));
    }
    

    public function store($id)
    {
        $user_id=Auth::user()->id;
        $user_name=Auth::user()->name;
        $plans=DB::table('plans')->where('id',$id)->first();
       
        $data=array(
            $stripe_name=$plans->name,
            $stripe_plan =$plans->stripe_plan,
        );
       
        $user = User::find(Auth::user()->id);
        $user->createAsStripeCustomer();
        
        //or use session to store name and plan
         return view('update-payment-method', [
            'intent' => $user->createSetupIntent()
        ])->with(compact('plans'));
        //return redirect(route('home'));
    }
    public function subscribe(Request $request)
    {
        $stripeToken=$request->stripeToken;
        $stripe_name=$request->stripe_name;
        $stripe_plan = $request->stripe_plan;
        $user = User::find(Auth::user()->id);
        $id=Auth::user()->id;
        $subscription=DB::table('subscriptions')->where([['user_id',$id],['stripe_status','active']])->get();
                    if ($user->subscribed($stripe_name)) 
                    {       
                        foreach($subscription as $sub) 
                        {      
                            if(($sub->stripe_status) == 'active')
                            {
                                return redirect(route('home'))->with('success','you are already suscribed to '.$stripe_name." ".'plans'); 
                            }
                        }
                    }
                    else
                    {
                        $subscription=$user->newSubscription( $stripe_name,$stripe_plan )->create($stripeToken);
                        return redirect(route('home'))->with('success','sucessfully suscribed to '.$stripe_name." ".'plans');
                    }
    }
}
