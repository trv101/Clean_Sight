@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-8">
    <!-- Back Button with Conditional Redirect -->
    @if(request()->query('from_dashboard') == 'true')
        <a href="{{ route('dashboard') }}" 
           class="inline-block px-4 py-2 bg-cyan-400 text-white font-semibold rounded-md shadow hover:bg-cyan-500 transition mb-4">
            Back
        </a>
    @else
        <a href="{{ route('incidents.index') }}" 
           class="inline-block px-4 py-2 bg-cyan-400 text-white font-semibold rounded-md shadow hover:bg-cyan-500 transition mb-4">
            Back
        </a>
    @endif
    
    @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('incidents.store', ['from_dashboard' => request('from_dashboard')]) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required></textarea>
        </div>

        <!-- Incident Type (Cleaning Incident) -->
        <div>
            <label for="incident_type" class="block text-sm font-medium text-gray-700">Incident Type</label>
            <select name="incident_type" id="incident_type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" onchange="toggleCategories()">
                <option value="Cleaning">Cleaning</option>
                <option value="Safety">Safety</option>
                <option value="Equipment">Equipment</option>
                <option value="Staff">Staff</option>
            </select>
        </div>

        <!-- Category (Dynamically Updates Based on Incident Type) -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" disabled>
                <!-- Default Cleaning Categories -->
                <option value="Spillage">Spillage</option>
                <option value="Trash Overflow">Trash Overflow</option>
                <option value="Cleaning Supplies Issue">Cleaning Supplies Issue</option>
                <option value="Other Cleaning">Other</option>
            </select>
        </div>

        <!-- Impact -->
        <div>
            <label for="impact" class="block text-sm font-medium text-gray-700">Impact</label>
            <select name="impact" id="impact" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <!-- Urgency -->
        <div>
            <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label>
            <select name="urgency" id="urgency" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <!-- Hidden Priority (Calculated in Backend) -->
        <input type="hidden" name="priority" value="">

        <!-- Status (Default: Open) -->
        <input type="hidden" name="status" value="Open">

        <!-- Picture Upload (New Field) -->
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Upload Picture (Optional)</label>
            <input type="file" name="image" id="image" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200 mt-4">Submit Incident</button>
        </div>
    </form>
</div>

<script>
    function toggleCategories() {
        var type = document.getElementById("incident_type").value;
        var categorySelect = document.getElementById("category");

        // Disable the category select if no type is selected
        categorySelect.disabled = false;

        // Clear existing options
        categorySelect.innerHTML = '';

        // Dynamically populate categories based on incident type
        if (type === "Cleaning") {
            categorySelect.innerHTML = `
                <option value="Spillage">Spillage</option>
                <option value="Trash Overflow">Trash Overflow</option>
                <option value="Cleaning Supplies Issue">Cleaning Supplies Issue</option>
                <option value="Other Cleaning">Other</option>
            `;
        } else if (type === "Safety") {
            categorySelect.innerHTML = `
                <option value="Injury">Injury</option>
                <option value="Safety Hazard">Safety Hazard</option>
                <option value="Unsafe Behavior">Unsafe Behavior</option>
                <option value="Other Safety">Other</option>
            `;
        } else if (type === "Equipment") {
            categorySelect.innerHTML = `
                <option value="Broken Equipment">Broken Equipment</option>
                <option value="Equipment Malfunction">Equipment Malfunction</option>
                <option value="Misplaced/lost Equipment">Misplaced/lost Equipment</option>
                <option value="Other Equipment">Other</option>
            `;
        } else if (type === "Staff") {
            categorySelect.innerHTML = `
                <option value="Behavioral">Behavioral</option>
                <option value="Harassment/Conflict">Harassment/Conflict</option>
                <option value="Other">Other</option>
            `;
        }
    }

    // Automatically trigger the category update when the page is loaded
    window.onload = toggleCategories;
</script>
@endsection
