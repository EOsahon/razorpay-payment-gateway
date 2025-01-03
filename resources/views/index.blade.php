<!DOCTYPE html>
<html>
<head>
    <title>How To Integrate Razorpay Payment Gateway In Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>    
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Error!</strong> {{ $message }}
                </div>
            @endif
            {!! Session::forget('error') !!}
            @if($message = Session::get('success'))
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Success!</strong> {{ $message }}
                </div>
            @endif
            {!! Session::forget('success') !!}
            <div class="panel panel-default">
                <div class="panel-heading mt-4"><h4>Pay With Razorpay</h4></div>

                <div class="panel-body mt-4">
                    <form id="payment-form" action="{!! route('payment') !!}" method="POST">
                        <label for="amount">Enter Amount:</label>
                        <input class="form-control" type="number" id="amount" name="amount" min="1" placeholder="Enter amount" required>
                        <button class="btn btn-success mt-3" type="button" id="pay-button">Pay Now</button>
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    </form>
                </div>

                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                <script>
                    document.getElementById('pay-button').addEventListener('click', function (e) {
                        e.preventDefault();

                        const amountInput = document.getElementById('amount').value;

                        if (!amountInput || amountInput <= 0) {
                            alert('Please enter a valid amount.');
                            return;
                        }

                        // Convert to paise (1 INR = 100 paise)
                        const amountInPaise = amountInput * 100;

                        // Configure Razorpay checkout
                        const options = {
                            key: "{{ env('RAZOR_KEY') }}", // Replace with your Razorpay Key
                            amount: amountInPaise, // Amount in paise
                            currency: "INR",
                            name: "Your Company Name",
                            description: "Payment",
                            image: "your-logo-url.jpg", // Replace with your logo URL
                            prefill: {
                                name: "", // Optionally prefill the user's name
                                email: "" // Optionally prefill the user's email
                            },
                            theme: {
                                color: "#ff7529"
                            },
                            handler: function (response) {
                                // Submit form with Razorpay payment ID
                                const form = document.getElementById('payment-form');
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'razorpay_payment_id';
                                input.value = response.razorpay_payment_id;
                                form.appendChild(input);
                                form.submit();
                            }
                        };

                        // Open Razorpay checkout
                        const rzp = new Razorpay(options);
                        rzp.open();
                    });
                </script>

            </div>
        </div>
    </div>
</div>
</body>
</html>