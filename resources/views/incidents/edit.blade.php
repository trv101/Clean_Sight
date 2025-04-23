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
                    <option value="Cleaning" {{ old('incident_type', $incident->incident_type) == 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                    <option value="Safety" {{ old('incident_type', $incident->incident_type) == 'Safety' ? 'selected' : '' }}>Safety</option>
                    <option value="Equipment" {{ old('incident_type', $incident->incident_type) == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                    <option value="Staff" {{ old('incident_type', $incident->incident_type) == 'Staff' ? 'selected' : '' }}>Staff</option>
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

            if (type === "Cleaning") {
                categorySelect.innerHTML = ` 
                    <option value="Spillage" {{ old('category', $incident->category) == 'Spillage' ? 'selected' : '' }}>Spillage</option>
                    <option value="Trash Overflow" {{ old('category', $incident->category) == 'Trash Overflow' ? 'selected' : '' }}>Trash Overflow</option>
                    <option value="Cleaning Supplies Issue" {{ old('category', $incident->category) == 'Cleaning Supplies Issue' ? 'selected' : '' }}>Cleaning Supplies Issue</option>
                    <option value="Other Cleaning" {{ old('category', $incident->category) == 'Other Cleaning' ? 'selected' : '' }}>Other Cleaning</option>
                `;
            } else if (type === "Safety") {
                categorySelect.innerHTML = `
                    <option value="Injury" {{ old('category', $incident->category) == 'Injury' ? 'selected' : '' }}>Injury</option>
                    <option value="Safety Hazard" {{ old('category', $incident->category) == 'Safety Hazard' ? 'selected' : '' }}>Safety Hazard</option>
                    <option value="Unsafe Behavior" {{ old('category', $incident->category) == 'Unsafe Behavior' ? 'selected' : '' }}>Unsafe Behavior</option>
                    <option value="Other Safety" {{ old('category', $incident->category) == 'Other Safety' ? 'selected' : '' }}>Other Safety</option>
                `;
            } else if (type === "Equipment") {
                categorySelect.innerHTML = `
                    <option value="Broken Equipment" {{ old('category', $incident->category) == 'Broken Equipment' ? 'selected' : '' }}>Broken Equipment</option>
                    <option value="Equipment Malfunction" {{ old('category', $incident->category) == 'Equipment Malfunction' ? 'selected' : '' }}>Equipment Malfunction</option>
                    <option value="Misplaced/lost Equipment" {{ old('category', $incident->category) == 'Misplaced/lost Equipment' ? 'selected' : '' }}>Misplaced/lost Equipment</option>
                    <option value="Other Equipment" {{ old('category', $incident->category) == 'Other Equipment' ? 'selected' : '' }}>Other Equipment</option>
                `;
            } else if (type === "Staff") {
                categorySelect.innerHTML = `
                    <option value="Behavioral" {{ old('category', $incident->category) == 'Behavioral' ? 'selected' : '' }}>Behavioral</option>
                    <option value="Harassment/Conflict" {{ old('category', $incident->category) == 'Harassment/Conflict' ? 'selected' : '' }}>Harassment/Conflict</option>
                    <option value="Other" {{ old('category', $incident->category) == 'Other' ? 'selected' : '' }}>Other</option>
                `;
            }
        }

        // Automatically trigger the category update when the page is loaded
        window.onload = toggleCategories;
    </script>
@endsection
