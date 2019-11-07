@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="container">
                        <h2>Subscription plans</h2>
                        @foreach($plans as $plan)
                        <li class="list-group-item clearfix">
                            <div class="pull-left">
                                <h5>{{ $plan->name }}</h5>
                                <h5>${{ number_format($plan->cost, 2) }} monthly</h5>
                                <h5>{{ $plan->description }}</h5>
                                @php $stripe_plan=$plan->stripe_plan ;
                                $user_id=Auth::user()->id; $yes=false;@endphp
                                                        
                                @foreach($subscription as $sub)
                                    @if((($sub->stripe_plan)== $stripe_plan) && (($sub->user_id) == $user_id))
                                        @php $yes=true; @endphp
                                    @endif

                                   
                                @endforeach
                                @if($yes)
                                <h5>already subscribed !!</h5>
                                <a href="{{ route('subscription.cancel',$sub->stripe_id) }}" class="btn btn-outline-dark pull-right">Cancel Subscription</a>
                                @else
                                <a href="{{ route('plans.store',$plan->id) }}" class="btn btn-outline-dark pull-right">Choose</a>
                                @endif
                            </div>
                           
                        </li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
