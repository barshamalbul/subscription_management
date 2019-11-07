
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
                <div class="form control mb-2">
                 {{-- @php dd($plans->name) @endphp  --}}
                    <input id="card-holder-name" class="col-sm-10" type="text">
                </div>
<!-- Stripe Elements Placeholder -->
                <div id="card-element"></div>
                <button id="card-button" class="mt-2" data-secret="{{ $intent->client_secret }}" >
                    Update Payment Method
                </button>

            <form action="{{route('plans.subscribe')}}" method="POST">
                {!! csrf_field() !!}
            <input type="hidden" name="stripe_name" value="{{$plans->name}}">
            <input type="hidden" name="stripe_plan" value="{{ $plans->stripe_plan }}">
            <input type="hidden" name="stripeToken" id="stripe_token" >
            <input type="submit" style="display:none" id="send_test">
            </form>

                <script src="https://js.stripe.com/v3/"></script>

                <script>
                    const stripe = Stripe('pk_test_J7offunL9KcPXVJBTi9u6uOY00TFgbwgTC');

                    const elements = stripe.elements();
                    const cardElement = elements.create('card');

                    cardElement.mount('#card-element');

                    const cardHolderName = document.getElementById('card-holder-name');
                    const cardButton = document.getElementById('card-button');
                    const clientSecret = cardButton.dataset.secret;

                cardButton.addEventListener('click', async (e) => {
                    const { setupIntent, error } = await stripe.handleCardSetup(
                        clientSecret, cardElement, {
                            payment_method_data: {
                                billing_details: { name: cardHolderName.value }
                            }
                        }
                    );

                    if (error) {
                        console.log(error);
                        alert(error);
                    } else {
                        document.getElementById('stripe_token').value=setupIntent.payment_method;
                        document.getElementById('send_test').click();
                        //send a post request to plans.test
                    }
                });
                </script>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection