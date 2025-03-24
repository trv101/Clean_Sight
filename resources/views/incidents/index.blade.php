@extends('layouts.app')

@section('content')

    

    <div class="max-w-8xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-4">Incidents</h1>
        @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
        @endif
        <a href="{{ route('incidents.create') }}" 
        class="bg-green-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-green-700 transition">
        + Report Incident
        </a>

        <table class="table-auto w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">User
                        ID</th>
                    <th class="p-2 border">Reported Date</th>
                    <th class="p-2 border">Title</th>
                    <th class="p-2 border">Description</th>
                    <th class="p-2 border">Category</th>
                    <th class="p-2 border">Priority</th>
                    <th class="p-2 border">Assigned Department</th>
                    <th class="p-2 border">Corrective Action</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Last Edit Date</th>
                    <th class="p-2 border">Last Editor ID</th>
                   
                    <th class="p-2 border"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incidents as $incident)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $incident->id }}</td>
                        <td class="p-3 border">{{ $incident->user_id }}</td>
                        <td class="p-3 border">{{ $incident->created_at->format('Y-m-d') }}</td>
                        <td class="p-3 border">{{ $incident->title }}</td>
                        <td class="p-3 border ">{{ $incident->description }}</td>
                        <td class="p-3 border ">{{ $incident->category }}</td>
                        <td class="p-3 border ">{{ $incident->priority }}</td>
                        <td class="p-3 border ">{{ $incident->assigned_department }}</td>
                        <td class="p-3 border ">{{ $incident->corrective_action }}</td>
                        <td class="p-3 border ">{{ $incident->status}}</td>
                        <td class="p-3 border ">{{ $incident->updated_at->format('Y-m-d') }}</td>
                        <td class="p-3 border">{{ $incident->updated_by_user_id }}</td>
                        <td class="p-3 border">
                            <form method="POST" action="{{ route('incidents.destroy', $incident->id) }}" class="inline-flex space-x-2">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('incidents.show', $incident->id)}}" class="bg-cyan-400 text-white font-semibold px-4 py-2 rounded-md hover:bg-cyan-500 transition">Show</a>
                                <a href="{{ route('incidents.edit', $incident->id)}}" class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">Edit</a>
                                <button class="bg-red-600 text-white font-semibold px-3 py-1.5 rounded-md hover:bg-red-700 transition">Delete</button>
                            </form>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
