@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-4">Report Incident</h1>
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

    <form action="{{ route('incidents.store') }}" method="POST" class="space-y-4">
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

        <!-- Incident Type (IT or HR) -->
        <div>
            <label for="incident_type" class="block text-sm font-medium text-gray-700">Incident Type</label>
            <select name="incident_type" id="incident_type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" onchange="toggleCategories()">
                <option value="IT">IT</option>
                <option value="HR">HR</option>
            </select>
        </div>

        <!-- Category (Dynamically Updates Based on Incident Type) -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <!-- IT Categories -->
                <optgroup label="IT Incidents">
                    <option value="Network">Network</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Software">Software</option>
                    <option value="Security">Security</option>
                    <option value="Other">Other</option>
                </optgroup>
                <!-- HR Categories -->
                <optgroup label="HR Incidents">
                    <option value="Workplace Conflict">Workplace Conflict</option>
                    <option value="Harassment">Harassment</option>
                    <option value="Policy Violation">Policy Violation</option>
                    <option value="Unauthorized Entry">Unauthorized Entry</option>
                    <option value="Theft">Theft</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Other HR">Other HR</option>
                </optgroup>
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
