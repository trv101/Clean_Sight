@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-4">Update Incident</h1>
        <a href="{{ route('incidents.index') }}" 
        class="inline-block px-4 py-2 bg-cyan-400 text-white font-semibold rounded-md shadow hover:bg-cyan-500 transition mb-4">
        Back</a>

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
        <form action="{{ route('incidents.update', $incident->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT') <!-- Use PATCH method for updates -->

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

            <!-- Incident Type (IT or HR) -->
            <div>
                <label for="incident_type" class="block text-sm font-medium text-gray-700">Incident Type</label>
                <select name="incident_type" id="incident_type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" onchange="toggleCategories()">
                    <option value="IT" {{ old('incident_type', $incident->incident_type) == 'IT' ? 'selected' : '' }}>IT</option>
                    <option value="HR" {{ old('incident_type', $incident->incident_type) == 'HR' ? 'selected' : '' }}>HR</option>
                </select>
            </div>

            <!-- Category (Dynamically Updates Based on Incident Type) -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                    <!-- IT Categories -->
                    <optgroup label="IT Incidents">
                        <option value="Network" {{ old('category', $incident->category) == 'Network' ? 'selected' : '' }}>Network</option>
                        <option value="Hardware" {{ old('category', $incident->category) == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                        <option value="Software" {{ old('category', $incident->category) == 'Software' ? 'selected' : '' }}>Software</option>
                        <option value="Security" {{ old('category', $incident->category) == 'Security' ? 'selected' : '' }}>Security</option>
                        <option value="Other" {{ old('category', $incident->category) == 'Other' ? 'selected' : '' }}>Other</option>
                    </optgroup>
                    <!-- HR Categories -->
                    <optgroup label="HR Incidents">
                        <option value="Workplace Conflict" {{ old('category', $incident->category) == 'Workplace Conflict' ? 'selected' : '' }}>Workplace Conflict</option>
                        <option value="Harassment" {{ old('category', $incident->category) == 'Harassment' ? 'selected' : '' }}>Harassment</option>
                        <option value="Policy Violation" {{ old('category', $incident->category) == 'Policy Violation' ? 'selected' : '' }}>Policy Violation</option>
                        <option value="Unauthorized Entry" {{ old('category', $incident->category) == 'Unauthorized Entry' ? 'selected' : '' }}>Unauthorized Entry</option>
                        <option value="Theft" {{ old('category', $incident->category) == 'Theft' ? 'selected' : '' }}>Theft</option>
                        <option value="Emergency" {{ old('category', $incident->category) == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="Other HR" {{ old('category', $incident->category) == 'Other HR' ? 'selected' : '' }}>Other HR</option>
                    </optgroup>
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
        
            <!-- Corrective Action Update -->
            <div class="mb-4">
                <label for="corrective_action" class="block text-sm font-medium text-gray-700">Corrective Action</label>
                <textarea name="corrective_action" id="corrective_action" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('corrective_action', $incident->corrective_action) }}</textarea>
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

            if (type === "IT") {
                categorySelect.innerHTML = `
                    <optgroup label="IT Incidents">
                        <option value="Network">Network</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Security">Security</option>
                        <option value="Other">Other</option>
                    </optgroup>
                `;
            } else {
                categorySelect.innerHTML = `
                    <optgroup label="HR Incidents">
                        <option value="Workplace Conflict">Workplace Conflict</option>
                        <option value="Harassment">Harassment</option>
                        <option value="Policy Violation">Policy Violation</option>
                        <option value="Unauthorized Entry">Unauthorized Entry</option>
                        <option value="Theft">Theft</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Other HR">Other HR</option>
                    </optgroup>
                `;
            }
        }
    </script>
@endsection
