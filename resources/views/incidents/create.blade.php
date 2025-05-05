@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-8">

    <!-- Back Button to Previous Page -->
    <a href="javascript:history.back()" 
       class="inline-block px-4 py-2 bg-cyan-400 text-white font-semibold rounded-md shadow hover:bg-cyan-500 transition mb-4">
       Back
    </a>

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

        <!-- Incident Type (Maintenance) -->
        <div>
            <label for="incident_type" class="block text-sm font-medium text-gray-700">Incident Type</label>
            <select name="incident_type" id="incident_type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" onchange="toggleCategories()">
                <option value="Electrical maintenance">Electrical maintenance</option>
                <option value="Plumbing maintenance">Plumbing maintenance</option>
                <option value="Garden Maintenance">Garden Maintenance</option>
                <option value="Cleaning Maintenance">Cleaning Maintenance</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Category (Dynamically Updates Based on Incident Type) -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" disabled>
                <!-- Default categories will be dynamically populated based on Incident Type -->
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

        <!-- Picture Upload -->
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700">Upload Picture (Optional)</label>
            <input type="file" name="photo[]" id="photo" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" multiple>
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

        // Enable the category select if a type is selected
        categorySelect.disabled = false;

        // Clear existing options
        categorySelect.innerHTML = '';

        // Dynamically populate categories based on incident type
        if (type === "Electrical maintenance") {
            categorySelect.innerHTML = `
                <option value="General repairs">General repairs</option>
                <option value="Power Outages">Power Outages</option>
                <option value="AC/Fan Issues">AC/Fan Issues</option>
                <option value="Lighting Problems">Lighting Problems</option>
                <option value="Electrical Other">Other</option>
            `;
        } else if (type === "Plumbing maintenance") {
            categorySelect.innerHTML = `
                <option value="Leaking Pipes">Leaking Pipes</option>
                <option value="Clogged Drains">Clogged Drains</option>
                <option value="Water Pressure">Water Pressure</option>
                <option value="Broken Faucets/Toilets">Broken Faucets/Toilets</option>
                <option value="Plumbing Other">Other</option>
            `;
        } else if (type === "Garden Maintenance") {
            categorySelect.innerHTML = `
                <option value="Lawn Care">Lawn Care</option>
                <option value="Fencing/Boundary Issues">Fencing/Boundary Issues</option>
                <option value="Garden Other">Other</option>
            `;
        } else if (type === "Cleaning Maintenance") {
            categorySelect.innerHTML = `
                <option value="Not Cleaned">Not Cleaned</option>
                <option value="Trash Overflow">Trash Overflow</option>
                <option value="Cleaning Other">Other</option>
            `;
        } else if (type === "Other") {
            categorySelect.innerHTML = `
                <option value="Other">Other</option>
            `;
        }
    }

    // Automatically trigger the category update when the page is loaded
    window.onload = toggleCategories;
</script>
@endsection
