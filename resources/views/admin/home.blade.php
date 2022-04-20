@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>{{ __('Dashboard') }}</span>
                        <a class="btn btn-sm btn-primary pull-right" href="{{ route('admin.form.create') }}">Create
                            Form</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <th>S.No</th>
                                <th>Form Title</th>
                                <th>Opened</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($forms as $form)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $form->title }}</td>
                                        <td>{{ $form->opened }}</td>
                                        <td>{{ $form->submitted }}</td>
                                        <td><a href="{{ route('admin.form', ['form' => $form->id]) }}">View</a></td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach()
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
