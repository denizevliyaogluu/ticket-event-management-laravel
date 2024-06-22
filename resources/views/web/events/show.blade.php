@extends('layouts.master')

@section('title', 'Event Details')

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
        body {
            font-family: Arial, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .event-image {
            max-width: 100%;
            height: auto;
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        .event-details {
            padding: 20px;
        }

        .event-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .event-info {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .ticket-categories {
            margin-top: 20px;
        }

        .category {
            margin-bottom: 20px;
        }

        .seat {
            display: inline-block;
            background-color: #f8f9fa;
            border: none;
            width: 30px;
            height: 30px;
            margin-right: 5px;
            margin-bottom: 5px;
            cursor: pointer;
            position: relative;
            border-radius: 5px;
        }

        .chair-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
        }

        .seat.selected {
            background-color: #007bff;
            color: #fff;
        }

        .seat.unavailable {
            cursor: not-allowed;
        }
    </style>

    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <div class="card" style="position: sticky; top: 0;">
                            <div class="row no-gutters">
                                <div class="col-md-12">
                                    @if ($event->image)
                                        <img src="{{ asset($event->image) }}" class="card-img event-image"
                                            alt="{{ $event->title }}" style="height: 450px">
                                    @else
                                        <img src="/upload/events/noimage.jpg" class="card-img event-image"
                                            alt="{{ $event->title }}" style="height: 450px">
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <div class="card-body event-details">
                                        <h2 class="event-title">{{ $event->title }}</h2>
                                        <p class="event-info"><strong>Date and Time:</strong> {{ $event->date_time }}</p>
                                        <p class="event-info"><strong>Location:</strong> {{ $event->location }}</p>
                                        
                                        <ul class="nav nav-tabs" id="eventTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="description-tab" data-toggle="modal" data-target="#descriptionModal" role="tab" aria-controls="description" aria-selected="true" style="color: black">Description</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="rules-tab" data-toggle="modal" data-target="#rulesModal" role="tab" aria-controls="rules" aria-selected="false" style="color: black">Event Rules</a>
                                            </li>
                                        </ul>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        @foreach ($event->getTicketCategories as $category)
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $category->name }}</h5>
                                    <p class="card-text">₺ {{ $category->price }}</p>
                                    @if ($category->remainingQuantity() > 0)
                                        <form
                                            action="{{ route('web.events.buyTicket', ['eventId' => $event->id, 'categoryId' => $category->id]) }}"
                                            method="POST" class="form-inline mt-3">
                                            @csrf
                                            <div class="form-group mr-2">
                                                <label for="quantity-{{ $category->id }}" class="sr-only">Quantity</label>
                                                <input type="number" id="quantity-{{ $category->id }}" name="quantity"
                                                    value="1" min="1"
                                                    max="{{ $category->remainingQuantity() }}" class="form-control"
                                                    placeholder="Quantity">
                                            </div>
                                            <button type="submit" class="btn btn-dark">Add to Cart</button>
                                        </form>
                                    @else
                                        <p class="text-danger">Sold Out</p>
                                    @endif
                                </div>
                            </div>
                            <br>
                        @endforeach

                        <br>
                        @if ($event->sitting_plan)
                            <div class="card">
                                <div class="card-body">
                                    <h3>Oturma Planı</h3>
                                    <img src="{{ asset($event->sitting_plan) }}" alt="{{ $event->name }}"
                                        class="card-img-top img-fluid">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! $event->description !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rulesModalLabel">Event Rules</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! $event->event_rules !!}
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.seat').click(function() {
                var $this = $(this);
                if (!$this.hasClass('unavailable')) {
                    $this.toggleClass('selected');
                }
            });
        });
    </script>
@endsection
