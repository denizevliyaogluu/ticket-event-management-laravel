@extends('layouts.master')

@section('title', 'Events')

@section('content')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    <style>
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            width: 100%;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
        }

        .city-icon {
            font-size: 1.5rem;
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
            transform: translateY(-2px);
        }

        .card-link {
            cursor: pointer;
        }
    </style>

    <div class="container mt-2">
        <div class="row">
            @foreach ($events as $event)
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('web.events.show', $event->id) }}" class="card-link">
                        <div class="card">
                            @if ($event->image)
                                <img src="{{ asset($event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                            @else
                                <img src="/upload/events/noimage.jpg" class="card-img-top" alt="{{ $event->title }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->title }}</h5>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-map-marker-alt city-icon" style="color: black"></i>
                                    <h6 class="card-text">{{ $event->location }}, {{ $event->getCity->name }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
