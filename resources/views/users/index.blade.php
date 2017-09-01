@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    <a href="{{ url('/user/add') }}">Add User</a>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Action</th>                                
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('editUser', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('deleteUser', ['id' => $user->id]) }}" class="btn btn-danger btn-sm delete-user">Delete</a>
                            </td>
                        </tr>
                        @endforeach

                        {{ $users->links() }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        $('.delete-user').on('click', function () {
            if (confirm("Are you sure to delete this department")) {
                return true;
            }
            return false;
        });
    });
</script>

@endsection
