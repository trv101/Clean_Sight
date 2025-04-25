@extends('layouts.app')

@section('content')
<div class="max-w-8xl mx-auto p-6 bg-white shadow rounded mt-8">
    <h1 class="text-2xl font-bold mb-4">Incidents</h1>
    
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif
    
    <a href="{{ route('incidents.create', ['from_dashboard' => 'false']) }}" 
        class="bg-green-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-green-700 transition">
        + Report Incident
    </a>

    <div class="flex justify-between items-center mt-8 mb-0">
        <form method="GET" action="{{ route('incidents.index') }}" class="mb-4 flex items-center" id="filterForm">
            <label class="mr-2 font-semibold">Filter by Status:</label>
            <label for="open" class="mr-2">
                <input type="checkbox" name="status[]" value="Open" {{ in_array('Open', $statuses ?? []) ? 'checked' : '' }}> Open
            </label>
            <label for="in_progress" class="mr-2">
                <input type="checkbox" name="status[]" value="In Progress" {{ in_array('In Progress', $statuses ?? []) ? 'checked' : '' }}> In Progress
            </label>
            <label for="on_hold" class="mr-2">
                <input type="checkbox" name="status[]" value="On Hold" {{ in_array('On Hold', $statuses ?? []) ? 'checked' : '' }}> On Hold
            </label>
            <label for="resolved" class="mr-2">
                <input type="checkbox" name="status[]" value="Resolved" {{ in_array('Resolved', $statuses ?? []) ? 'checked' : '' }}> Resolved
            </label>
            <label for="escalated" class="mr-2">
                <input type="checkbox" name="status[]" value="Escalated" {{ in_array('Escalated', $statuses ?? []) ? 'checked' : '' }}> Escalated
            </label>
        </form>
        
        <div class="my-4 flex items-center">
            <span class="font-semibold">Sort by Priority:</span>
            <a href="{{ route('incidents.index', ['sort_order' => 'asc']) }}" 
                class="text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded-md inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                </svg>
            </a> 
            
            <a href="{{ route('incidents.index', ['sort_order' => 'desc']) }}" 
                class="text-blue-600 hover:text-blue-800 font-medium px-1 py-1 rounded-md inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                </svg>
            </a>

            <a href="{{ route('incidents.index') }}" 
                class= text-gray-300 font-semibold text-sm px-1 py-0.5 rounded-md hover:bg-gray-500 transition">
                Reset
            </a>
        </div>
    </div>

    <table class="table-auto w-full border-collapse border border-gray-300 mt-0 text-center">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Reported Date</th>
                <th class="p-2 border">Title</th>
                <th class="p-2 border">Category</th>
                <th class="p-2 border">Priority</th>
                <th class="p-2 border">Assigned Department</th>
                <th class="p-2 border">Corrective Action</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Last Edit Date</th>
                <th class="p-2 border">Last Editor</th>
                <th class="p-2 border">Photo</th>
                <th class="p-2 border"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incidents as $incident)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border">{{ $incident->id }}</td>
                    <td class="p-3 border">{{ $incident->created_at->format('Y-m-d') }}</td>
                    <td class="p-3 border">{{ $incident->title }}</td>
                    <td class="p-3 border">{{ $incident->category }}</td>
                    <td class="p-3 border 
                        @if($incident->priority == 'High') bg-red-500/80
                        @elseif($incident->priority == 'Medium') bg-orange-500/80
                        @elseif($incident->priority == 'Low') bg-green-500/80
                        @endif">
                        {{ $incident->priority }}
                    </td>
                    <td class="p-3 border">{{ $incident->assigned_department }}</td>
                    <td class="p-3 border">{{ $incident->corrective_action }}</td>
                    
                    <td class="p-3 border
                            @if($incident->status == 'Resolved') bg-green-500/80
                            @elseif($incident->status == 'In Progress') bg-yellow-500/80
                            @elseif($incident->status == 'On Hold') bg-purple-500/80
                            @elseif($incident->status == 'Escalated') bg-red-500/80
                            @elseif($incident->status == 'Open') bg-blue-500/80
                            @endif">
                            {{ $incident->status }}
                        
                    </td>
                    <td class="p-3 border">{{ $incident->updated_at->format('Y-m-d') }}</td>
                    <td class="p-3 border">{{ $incident->updatedByUser ? $incident->updatedByUser->name : 'N/A' }}</td>
                    <!-- Centered Photo Column -->
                    <td class="p-3 border flex justify-center items-center">
                        @if($incident->photo)
                            <img 
                                src="{{ asset($incident->photo) }}" 
                                alt="Incident Photo" 
                                class="w-16 h-16 object-cover rounded-md cursor-pointer transition-transform duration-200 hover:scale-125"
                                onclick="showImageModal('{{ asset($incident->photo) }}')"
                            >
                        @else
                            <p>No image</p>
                        @endif
                    </td>
                    <td class="p-3 border">
                        <form method="POST" action="{{ route('incidents.destroy', $incident->id) }}" class="inline-flex space-x-2">
                            @csrf
                            @method('DELETE')
                            @can('incident-list')
                            <a href="{{ route('incidents.show', $incident->id)}}" class="bg-cyan-400 text-white font-semibold px-4 py-2 rounded-md hover:bg-cyan-500 transition">Show</a>
                            @endcan
                            @can('incident-edit')
                            <a href="{{ route('incidents.edit', $incident->id)}}" class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">Edit</a>
                            @endcan
                            @can('incident-delete')
                            <button class="bg-red-600 text-white font-semibold px-3 py-1.5 rounded-md hover:bg-red-700 transition">Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Full Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-70 flex items-center justify-center">
    <div class="relative max-w-3xl w-full">
        <button onclick="closeImageModal()" class="absolute top-2 right-2 text-white text-3xl font-bold">&times;</button>
        <img id="modalImage" src="" alt="Full Image" class="max-w-full max-h-[90vh] mx-auto rounded shadow-lg">
    </div>
</div>

<script>
    // Automatically submit the form when a checkbox is clicked
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('change', () => {
        filterForm.submit();
    });

    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
@endsection