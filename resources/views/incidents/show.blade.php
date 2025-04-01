@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-6 text-center">Incident Details</h1>
        
        <a href="{{ route('incidents.index') }}" 
            class="inline-block px-4 py-2 bg-cyan-400 text-white font-semibold rounded-md shadow hover:bg-cyan-500 transition mb-4">
            Back
        </a>

        <div class="space-y-6">
            <!-- Incident Information Cards -->
            <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Incident Information</h2>
                <p><strong class="font-semibold">Title:</strong> {{ $incident->title }}</p>
                <p><strong class="font-semibold">Description:</strong> {{ $incident->description }}</p>
                <p><strong class="font-semibold">Incident Type:</strong> {{ $incident->incident_type }}</p>
                <p><strong class="font-semibold">Category:</strong> {{ $incident->category }}</p>
                <p><strong class="font-semibold">Impact:</strong> {{ $incident->impact }}</p>
                <p><strong class="font-semibold">Urgency:</strong> {{ $incident->urgency }}</p>
            </div>

            <!-- Status and Priority Information -->
            <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Status & Priority</h2>
                <p><strong class="font-semibold">Status:</strong> {{ $incident->status }}</p>
                <p><strong class="font-semibold">Priority:</strong> {{ $incident->priority }}</p>
            </div>

            <!-- Assigned Department and User -->
            <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Assigned Information</h2>
                <p><strong class="font-semibold">Assigned Department:</strong> {{ $incident->assigned_department }}</p>
                <p><strong class="font-semibold">Created By:</strong> {{ $incident->user->name }} ({{ $incident->user->email }})</p>
                <p><strong class="font-semibold">Updated By:</strong> {{ $incident->updatedByUser->name ?? 'Not updated yet' }}</p>
            </div>

            <!-- Corrective Action -->
            <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Corrective Action</h2>
                <p><strong class="font-semibold">Corrective Action:</strong> {{ $incident->corrective_action ?? 'No corrective action yet.' }}</p>
            </div>

            <!-- Timestamps -->
            <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Timestamps</h2>
                <p><strong class="font-semibold">Created At:</strong> {{ $incident->created_at->format('Y-m-d H:i:s') }}</p>
                <p><strong class="font-semibold">Updated At:</strong> {{ $incident->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>

            <!-- Display last edit details -->
            @if($incident->last_edit_details)
                <div class="bg-gray-50 p-4 rounded-md shadow-sm mt-4">
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Last Edit Details</h2>
                    <p><strong class="font-semibold">Last Edit:</strong> {{ $incident->last_edit_details }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
