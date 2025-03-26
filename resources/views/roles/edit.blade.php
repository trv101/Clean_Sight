@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded mt-8">
        <h1 class="text-2xl font-bold mb-4">Update Role</h1>
        <a href="{{ route('roles.index') }}" 
        class="bg-cyan-400 text-white font-semibold px-4 py-2 rounded-md hover:bg-cyan-500 transition ">
        Back</a>
        
        <form method="POST" action="{{route('roles.update', $role->id ) }}">
            @csrf
            @method("PUT")
            
            <div class="mt-2">
                <label>Name:</label> <br>
                <input type="text" name="name" class="form.control" value="{{ $role->name }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-2">
                <h3>Permissions:</h3>
                @foreach ($permissions as $permission)
                    <label><input type="checkbox" name="permissions[{{ $permission->name }}]"
                        value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name)? 'checked':'' }}> {{ $permission->name }}</label> </br>
                @endforeach
            </div>

            <div class="mt-2">
                <button class="btn btn-success inline-block px-4 py-2 bg-green-600 text-white font-semibold rounded-md shadow hover:bg-green-700 transition mb-4"> Submit </button>
            </div>
        </form>
        
        
    </div>
@endsection
