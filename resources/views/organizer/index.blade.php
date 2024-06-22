@extends('layouts.master')

@section('title', 'Organizer Events')

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
            font-family: 'Arial', sans-serif;
        }

        .content-container {
            margin-top: 50px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-dark:hover {
            background-color: #23272b;
            border-color: #1d2124;
        }

        .table {
            margin-top: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .card-img-top {
            border-radius: 5px;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #343a40;
            color: #fff;
            border-bottom: none;
        }

        .modal-title {
            font-size: 1.5rem;
        }

        .close {
            color: #fff;
        }
    </style>

    <div class="container content-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-right mb-3">
                    <a href="{{ route('organizer.events.create') }}" class="btn btn-dark">Add Event</a>
                </div>

                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>
                                    @if ($event->image)
                                        <img src="{{ asset($event->image) }}" class="card-img-top" alt="{{ $event->name }}"
                                            style="max-width: 100px;">
                                    @else
                                        <img src="/upload/events/noimage.jpg" class="card-img-top" alt="No Image"
                                            style="max-width: 100px;">
                                    @endif
                                </td>
                                <td>{{ $event->title }}</td>
                                <td>{{ $event->date_time }}</td>
                                <td>{{ $event->location }}</td>
                                <td>
                                    @if ($event->status == 0)
                                        <button class="btn btn-secondary btn-sm mr-2" disabled>Edit</button>
                                    @else
                                        <a href="{{ route('organizer.events.update', $event->id) }}"
                                            class="btn btn-secondary btn-sm mr-2">Edit</a>
                                    @endif
                                    {{-- <a href="{{ route('organizer.events.delete', $event->id) }}" class="btn btn-sm btn-dark" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                    @if ($event->status == 1)
                                        <a href="{{ route('organizer.events.close', $event->id) }}"
                                            class="btn btn-sm btn-dark" onclick="return confirm('Are you sure?')">Close</a>
                                    @else
                                        <button type="button" class="btn btn-dark btn-sm">Closed</button>
                                    @endif
                                    <button type="button" class="btn btn-dark btn-sm" data-toggle="modal"
                                        data-target="#ticketsModal{{ $event->id }}">
                                        Show Tickets
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($events as $event)
        <div class="modal fade" id="ticketsModal{{ $event->id }}" tabindex="-1" role="dialog"
            aria-labelledby="ticketsModalLabel{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ticketsModalLabel{{ $event->id }}">Tickets for {{ $event->title }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @foreach ($event->getTicketCategories as $category)
                            <h6>{{ $category->name }}</h6>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Available Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->price }}</td>
                                        <td>{{ $category->ticket_quantity }}</td>
                                        <td>{{ $category->remainingQuantity() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
