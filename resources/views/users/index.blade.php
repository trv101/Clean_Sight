@extends('layouts.app')

@section('content')

    @if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3">
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-6xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-4">Users</h1>
        <a href="{{ route('users.create') }}" 
        class="inline-block px-4 py-2 bg-yellow-400 text-black font-semibold rounded-md shadow hover:bg--white transition mb-4">
        + Create User
        </a>

        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $user->id }}</td>
                        <td class="p-2 border">{{ $user->name }}</td>
                        <td class="p-2 border ">{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
