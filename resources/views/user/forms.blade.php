@extends('layouts.frontend')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (Session::has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">Forms</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <th>S.No</th>
                                <th>Form Title</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @if (count($forms) > 0)
                                    @php $i=1; @endphp
                                    @foreach ($forms as $form)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $form->title }}</td>
                                            <td><a href="{{ route('form.open', ['form' => $form->id]) }}">View</a></td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach()
                                @else
                                    <tr>
                                        <td class="text-center" colspan="3">No Data Found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
