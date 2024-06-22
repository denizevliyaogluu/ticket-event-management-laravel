@extends('layouts.master')

@section('title', 'Cart')

@section('content')
    <div class="col-lg-8 mx-auto">
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif
    </div>
    <br>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <h5 class="mb-3"><a href="{{ route('web.events.index') }}" class="text-body"><i
                                            class="fas fa-long-arrow-alt-left me-2"></i> Continue shopping</a></h5>
                                <hr>
                                @if (session('cart'))
                                    @foreach (session('cart') as $index => $ticketInfo)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div>
                                                            <img src="{{ asset($ticketInfo['image'] ?? '/upload/events/noimage.jpg') }}"
                                                                class="img-fluid rounded-circle"
                                                                alt="{{ $ticketInfo['event_name'] }}"
                                                                style="width: 65px; height: 65px; object-fit: cover;">
                                                        </div>

                                                        <div class="ms-3">
                                                            <h5>{{ $ticketInfo['event_name'] }}</h5>
                                                            <p class="small mb-0">{{ $ticketInfo['category_name'] }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div class="d-flex flex-row align-items-center"
                                                            style="width: 100px;">
                                                            <button class="btn btn-sm btn-outline-secondary change-quantity"
                                                                data-index="{{ $index }}"
                                                                data-action="decrease">-</button>
                                                            <h5 class="fw-normal mb-0 mx-2">
                                                                {{ $ticketInfo['quantity'] }}</h5>
                                                            <button class="btn btn-sm btn-outline-secondary change-quantity"
                                                                data-index="{{ $index }}"
                                                                data-action="increase">+</button>
                                                        </div>
                                                        <div style="width: 100px;">
                                                            <h5 class="mb-0">
                                                                ₺{{ number_format($ticketInfo['price'], 2) }}</h5>
                                                        </div>
                                                        {{-- <a href="{{ route('web.events.removeFromCart', $ticketInfo['id']) }}" style="color: #cecece;"><i class="fas fa-trash-alt"></i></a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="total-amount mt-4 text-right">
                                        <h5><strong>Total Amount:</strong> ₺{{ number_format($totalAmount, 2) }}</h5>
                                    </div>
                                @else
                                    <div class="alert alert-info mt-3" role="alert">
                                        No items in cart
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-5">
                                <div class="card bg-dark text-white rounded-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="mb-0">Card details</h5>
                                        </div>
                                        <p class="small mb-2">Card type</p>
                                        <a href="#!" class="text-white"><i
                                                class="fab fa-cc-mastercard fa-2x me-2"></i></a>
                                        <a href="#!" class="text-white"><i class="fab fa-cc-visa fa-2x me-2"></i></a>
                                        <a href="#!" class="text-white"><i class="fab fa-cc-amex fa-2x me-2"></i></a>
                                        <a href="#!" class="text-white"><i class="fab fa-cc-paypal fa-2x"></i></a>
                                        <form class="mt-4">
                                            <div class="form-outline form-white mb-4">
                                                <input type="text" id="typeName" class="form-control form-control-lg"
                                                    placeholder="Cardholder's Name" />
                                                <label class="form-label" for="typeName">Cardholder's Name</label>
                                            </div>
                                            <div class="form-outline form-white mb-4">
                                                <input type="text" id="typeText" class="form-control form-control-lg"
                                                    placeholder="1234 5678 9012 3457" minlength="19" maxlength="19" />
                                                <label class="form-label" for="typeText">Card Number</label>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="form-outline form-white">
                                                        <input type="text" id="typeExp"
                                                            class="form-control form-control-lg" placeholder="MM/YYYY"
                                                            size="7" minlength="7" maxlength="7" />
                                                        <label class="form-label" for="typeExp">Expiration</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-outline form-white">
                                                        <input type="password" id="typeCvv"
                                                            class="form-control form-control-lg"
                                                            placeholder="&#9679;&#9679;&#9679;" size="1"
                                                            minlength="3" maxlength="3" />
                                                        <label class="form-label" for="typeCvv">Cvv</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-2">Subtotal</p>
                                            <p class="mb-2">₺{{ number_format($totalAmount, 2) }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between mb-4">
                                            <p class="mb-2">Total(Incl. taxes)</p>
                                            <p class="mb-2">₺{{ number_format($totalAmount, 2) }}</p>
                                        </div>
                                        <button type="button" class="btn btn-info btn-block btn-lg" id="checkoutBtn">
                                            <div class="d-flex justify-content-between">
                                                <span>₺{{ number_format($totalAmount, 2) }}</span>
                                                <span>Checkout <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <script>
        document.getElementById('checkoutBtn').addEventListener('click', function() {
            if (confirm("Are you sure?")) {
                var form = document.createElement('form');
                form.action = "{{ route('web.events.confirmPurchase') }}";
                form.method = "POST";
                form.classList.add("mt-4");
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            } else {
                return false;
            }
        });

        document.querySelectorAll('.change-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                const action = this.getAttribute('data-action');
                const form = document.createElement('form');
                form.action = "{{ route('web.events.updateCart') }}";
                form.method = "POST";
                form.classList.add("d-none");
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="index" value="${index}">
                    <input type="hidden" name="action" value="${action}">
                `;
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
@endsection
