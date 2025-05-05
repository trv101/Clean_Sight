@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-8">
    <h1 class="text-2xl font-bold mb-4">Update Incident</h1>
    <a href="{{ route('incidents.index') }}" 
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

    <!-- Form to edit the incident -->
    <form action="{{ route('incidents.update', $incident->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Use PUT method for updates -->

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('title', $incident->title) }}" required>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>{{ old('description', $incident->description) }}</textarea>
        </div>

        <!-- Incident Type -->
        <div>
            <label for="incident_type" class="block text-sm font-medium text-gray-700">Incident Type</label>
            <select name="incident_type" id="incident_type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" onchange="toggleCategories()">
                <option value="Electrical maintenance" {{ old('incident_type', $incident->incident_type) == 'Electrical maintenance' ? 'selected' : '' }}>Electrical maintenance</option>
                <option value="Plumbing maintenance" {{ old('incident_type', $incident->incident_type) == 'Plumbing maintenance' ? 'selected' : '' }}>Plumbing maintenance</option>
                <option value="Garden Maintenance" {{ old('incident_type', $incident->incident_type) == 'Garden Maintenance' ? 'selected' : '' }}>Garden Maintenance</option>
                <option value="Cleaning Maintenance" {{ old('incident_type', $incident->incident_type) == 'Cleaning Maintenance' ? 'selected' : '' }}>Cleaning Maintenance</option>
                <option value="Other" {{ old('incident_type', $incident->incident_type) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                <!-- Dynamically populated options will go here -->
            </select>
        </div>

        <!-- Impact -->
        <div>
            <label for="impact" class="block text-sm font-medium text-gray-700">Impact</label>
            <select name="impact" id="impact" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Low" {{ old('impact', $incident->impact) == 'Low' ? 'selected' : '' }}>Low</option>
                <option value="Medium" {{ old('impact', $incident->impact) == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ old('impact', $incident->impact) == 'High' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <!-- Urgency -->
        <div>
            <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label>
            <select name="urgency" id="urgency" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Low" {{ old('urgency', $incident->urgency) == 'Low' ? 'selected' : '' }}>Low</option>
                <option value="Medium" {{ old('urgency', $incident->urgency) == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ old('urgency', $incident->urgency) == 'High' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Open" {{ $incident->status == 'Open' ? 'selected' : '' }}>Open</option>
                <option value="In Progress" {{ $incident->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Resolved" {{ $incident->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="On Hold" {{ $incident->status == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                <option value="Escalated" {{ $incident->status == 'Escalated' ? 'selected' : '' }}>Escalated</option>
            </select>
        </div>

        <!-- Corrective Action -->
        <div class="mb-4">
            <label for="corrective_action" class="block text-sm font-medium text-gray-700">Corrective Action</label>
            <textarea name="corrective_action" id="corrective_action" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('corrective_action', $incident->corrective_action) }}</textarea>
        </div>

        <!-- Image Upload (Only if there is a file) -->
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700">Upload New Image (Optional)</label>
            <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
            
            <!-- Display current image -->
            @if($incident->photo)
                <div class="mt-2">
                    <img src="{{ asset( $incident->photo) }}" alt="Incident Image" class="w-32 h-32 object-cover rounded-md">
                </div>
            @else
                <p class="mt-2 text-gray-600">No image available</p>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200 mt-4">Update Incident</button>
        </div>
    </form>
</div>

<script>
    // Keep the toggleCategories function for dynamic category updates
    function toggleCategories() {
        var type = document.getElementById("incident_type").value;
        var categorySelect = document.getElementById("category");

        // Remove existing options
        categorySelect.innerHTML = "";

        if (type === "Electrical maintenance") {
            categorySelect.innerHTML = ` 
                <option value="General repairs" {{ old('category', $incident->category) == 'General repairs' ? 'selected' : '' }}>General repairs</option>
                <option value="Power Outages" {{ old('category', $incident->category) == 'Power Outages' ? 'selected' : '' }}>Power Outages</option>
                <option value="AC/Fan Issues" {{ old('category', $incident->category) == 'AC/Fan Issues' ? 'selected' : '' }}>AC/Fan Issues</option>
                <option value="Lighting Problems" {{ old('category', $incident->category) == 'Lighting Problems' ? 'selected' : '' }}>Lighting Problems</option>
                <option value="Electrical Other" {{ old('category', $incident->category) == 'Electrical Other' ? 'selected' : '' }}>Other</option>
            `;
        } else if (type === "Plumbing maintenance") {
            categorySelect.innerHTML = ` 
                <option value="Leaking Pipes" {{ old('category', $incident->category) == 'Leaking Pipes' ? 'selected' : '' }}>Leaking Pipes</option>
                <option value="Clogged Drains" {{ old('category', $incident->category) == 'Clogged Drains' ? 'selected' : '' }}>Clogged Drains</option>
                <option value="Water Pressure" {{ old('category', $incident->category) == 'Water Pressure' ? 'selected' : '' }}>Water Pressure</option>
                <option value="Broken Faucets/Toilets" {{ old('category', $incident->category) == 'Broken Faucets/Toilets' ? 'selected' : '' }}>Broken Faucets/Toilets</option>
                <option value="Plumbing Other" {{ old('category', $incident->category) == 'Plumbing Other' ? 'selected' : '' }}>Other</option>
            `;
        } else if (type === "Garden Maintenance") {
            categorySelect.innerHTML = ` 
                <option value="Lawn Care" {{ old('category', $incident->category) == 'Lawn Care' ? 'selected' : '' }}>Lawn Care</option>
                <option value="Fencing/Boundary Issues" {{ old('category', $incident->category) == 'Fencing/Boundary Issues' ? 'selected' : '' }}>Fencing/Boundary Issues</option>
                <option value="Garden Other" {{ old('category', $incident->category) == 'Garden Other' ? 'selected' : '' }}>Other</option>
            `;
        } else if (type === "Cleaning Maintenance") {
            categorySelect.innerHTML = ` 
                <option value="Not Cleaned" {{ old('category', $incident->category) == 'Not Cleaned' ? 'selected' : '' }}>Not Cleaned</option>
                <option value="Trash Overflow" {{ old('category', $incident->category) == 'Trash Overflow' ? 'selected' : '' }}>Trash Overflow</option>
                <option value="Cleaning Other" {{ old('category', $incident->category) == 'Cleaning Other' ? 'selected' : '' }}>Other</option>
            `;
        } else if (type === "Other") {
            categorySelect.innerHTML = ` 
                <option value="Other" {{ old('category', $incident->category) == 'Other' ? 'selected' : '' }}>Other</option>
            `;
        }
    }

    // Automatically trigger the category update when the page is loaded
    window.onload = toggleCategories;
</script>
@endsection
