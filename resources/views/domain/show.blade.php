@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="mt-5 mb-3">Site: {{ $domain->name }}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap">
            <tr>
                <th>id</th>
                <th>{{ $domain->id }}</th>
            </tr>
            <tr>
                <td>name</td>
                <td>{{ $domain->name }}</td>
            </tr>
            <tr>
                <td>created_at</td>
                <td>{{ $domain->created_at }}</td>
            </tr>
            <tr>
                <td>updated_at</td>
                <td>{{ $domain->updated_at }}</td>
            </tr>
        </table>
    </div>
    <h2 class="mt-5 mb-3">Checks</h2>
    <form class="mb-3" method="post" action="{{ route('domains.checks.store', $domain->id) }}">
        @csrf
        <input type="submit" class="btn btn-primary" value="Run check">
    </form>
    <table class="table table-bordered table-hover text-nowrap">
        <tr>
            <th>Id</th>
            <th>Created At</th>
        </tr>
        @foreach ($domain_checks as $domain_check)
            <tr>
                <td>{{ $domain_check->id }}</td>
                <td>{{ $domain_check->created_at }}</td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
