@extends('layouts.app')

@section('content')

    

    <div class="max-w-6xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-4">Roles</h1>
        @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
        @endif
        <a href="{{ route('roles.create') }}" 
        class="bg-green-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-green-700 transition">
        + Create Role
        </a>

        <table class="table-auto w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border" style="width: 60px;">ID</th>
                    <th class="p-2 border">Name</th>
                  
                    <th class="p-2 border" style="width: 200px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $role->id }}</td>
                        <td class="p-3 border">{{ $role->name }}</td>
                        
                        <td class="p-3 border ">
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="inline-flex space-x-2">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('roles.show', $role->id)}}" class="bg-cyan-400 text-white font-semibold px-4 py-2 rounded-md hover:bg-cyan-500 transition">
                                    Show
                                </a>
                                <a href="{{ route('roles.edit', $role->id)}}" class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                    Edit
                                </a>
                                <button class="bg-red-600 text-white font-semibold px-3 py-1.5 rounded-md hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
