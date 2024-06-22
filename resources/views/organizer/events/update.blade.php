@extends('layouts.master')

@section('title', 'Update Event')

@section('content')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
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

        h1 {
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 300;
            color: #343a40;
        }

        .form-group label {
            font-weight: 500;
            color: #343a40;
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

        .card-header {
            background-color: #343a40;
            color: #fff;
        }

        .form-control-file {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 8px;
            width: 100%;
        }

        .ticket_category {
            margin-bottom: 10px;
        }

        .ticket_category input {
            margin-bottom: 5px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: #545b62;
            border-color: #4e555b;
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: transparent;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 8px;
            width: 100%;
            cursor: pointer;
        }

        .select-arrow {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .select-arrow::after {
            content: '\25BC';
            font-size: 16px;
            color: #495057;
        }
        
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>

    <div class="container content-container">
        <h1>Update Event</h1>

        <form action="{{ route('organizer.events.updatePost', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $event->title }}"
                    required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required>{{ $event->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="event_rules">Event Rules:</label>
                <textarea class="form-control" id="event_rules" name="event_rules" required>{{ $event->event_rules }}</textarea>
            </div>
            <div class="form-group">
                <label for="date_time">Date and Time:</label>
                <input type="datetime-local" class="form-control" id="date_time" name="date_time"
                    value="{{ $event->date_time }}" required>
            </div>
            <div class="form-group select-wrapper">
                <label for="city_id">City:</label>
                <select name="city_id" class="form-select" id="city_id" required>
                    <option value="">Select City</option>
                    @foreach (App\Models\Cities::orderBy('id', 'ASC')->get() as $city)
                        <option value="{{ $city->id }}" {{ $event->city_id == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}</option>
                    @endforeach
                </select>
                <div class="select-arrow"></div>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ $event->location }}"
                    required>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image">
                    <label class="custom-file-label" for="image">Choose file</label>
                </div>
                <small class="form-text text-muted">You can upload a new image if you want to update it.</small>
            </div>
            <div class="card">
                <div class="card-header">Tickets</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="ticket_categories">Categories:</label>
                        <div id="ticket_categories">
                            @foreach ($event->getTicketCategories as $category)
                                <div class="ticket_category">
                                    <input type="text" class="form-control"
                                        name="ticket_categories[{{ $category->id }}][name]" placeholder="Category Name"
                                        value="{{ $category->name }}" required>
                                    <input type="number" class="form-control"
                                        name="ticket_categories[{{ $category->id }}][price]" placeholder="Price"
                                        value="{{ $category->price }}" required>
                                    <input type="number" class="form-control"
                                        name="ticket_categories[{{ $category->id }}][quantity]" placeholder="Quantity"
                                        value="{{ $category->ticket_quantity }}" required>
                                    <input type="hidden" name="ticket_categories[{{ $category->id }}][session]"
                                        value="0">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center">
                <button type="submit" class="btn btn-dark mt-3">Update Event</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
         ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#event_rules'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
