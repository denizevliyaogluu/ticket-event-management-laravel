@extends('layouts.master')

@section('title', 'Create Event')

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
        <form action="{{ route('organizer.events.createPost') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Title:</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label>Event Rules:</label>
                <textarea class="form-control" id="event_rules" name="event_rules" required>{{ old('event_rules') }}</textarea>
            </div>
            <div class="form-group">
                <label>Date and Time:</label>
                <input type="datetime-local" class="form-control" name="date_time" value="{{ old('date_time') }}" required>
            </div>
            <div class="form-group">
                <label>Location:</label>
                <input type="text" class="form-control" name="location" value="{{ old('location') }}" required>
            </div>
            <div class="form-group select-wrapper">
                <label for="city_id">City:</label>
                <select name="city_id" class="form-select" id="city_id" required>
                    <option value="">Select City</option>
                    @foreach (App\Models\Cities::orderBy('id', 'ASC')->get() as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
                <div class="select-arrow"></div>
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Event Image:</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image">
                    <label class="custom-file-label" for="image">Choose file</label>
                </div>
                <small class="form-text text-muted">Please upload a high-resolution image (jpg, png).</small>
            </div>
            <div class="form-group">
                <label for="sitting_plan" class="form-label">Sitting Plan:</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="sitting_plan" name="sitting_plan">
                    <label class="custom-file-label" for="sitting_plan">Choose file</label>
                </div>
                <small class="form-text text-muted">Please upload a high-resolution image (jpg, png).</small>
            </div>
            <div class="card">
                <div class="card-header">Ticket Categories</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="ticket_categories">Categories:</label>
                        <div id="ticket_categories">
                            <div class="ticket_category">
                                <input type="text" class="form-control" name="ticket_categories[0][name]"
                                    placeholder="Category Name" required>
                                <input type="number" class="form-control" name="ticket_categories[0][price]"
                                    placeholder="Price" required>
                                <input type="number" class="form-control" name="ticket_categories[0][quantity]"
                                    placeholder="Quantity" required>
                                <input type="hidden" name="ticket_categories[0][session]" value="1">
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="addTicketCategory()">Add Category</button>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center mt-3">
                <button type="submit" class="btn btn-dark">Create Event</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function addTicketCategory() {
            var categoryCount = document.querySelectorAll('.ticket_category').length;

            var newCategory = document.createElement('div');
            newCategory.classList.add('ticket_category');
            newCategory.innerHTML = `
                <input type="text" class="form-control" name="ticket_categories[${categoryCount}][name]" placeholder="Category Name" required>
                <input type="number" class="form-control" name="ticket_categories[${categoryCount}][price]" placeholder="Price" required>
                <input type="number" class="form-control" name="ticket_categories[${categoryCount}][quantity]" placeholder="Quantity" required>
                <input type="hidden" name="ticket_categories[${categoryCount}][session]" value="1">
            `;

            document.getElementById('ticket_categories').appendChild(newCategory);
        }

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
