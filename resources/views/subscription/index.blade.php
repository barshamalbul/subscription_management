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
                        <h2>Subscribed plans</h2>
                        @foreach($subscription as $subscriptions)
                        <li class="list-group-item clearfix">
                            <div class="pull-left">
                                <h5>plan name: {{ $subscriptions->name }}</h5>
                                <h5>Stripe_status: {{ $subscriptions->stripe_status }}</h5>
                                 <h5>Quantity: {{ $subscriptions->quantity}}</h5>
                                 <a href="{{ route('subscription.increase',$subscriptions->stripe_id) }}" class="btn btn-outline-dark pull-right">Increase Subscription</a>
                                 <a href="{{ route('subscription.decrease',$subscriptions->stripe_id) }}" class="btn btn-outline-dark pull-right">Decrease Subscription</a>
                                <a href="{{ route('subscription.cancel',$subscriptions->stripe_id) }}" class="btn btn-outline-dark pull-right">Cancel Subscription</a>
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
