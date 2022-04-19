@extends('layouts.frontend')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
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
                                @php $i=1; @endphp
                                @foreach ($forms as $form)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $form->title }}</td>
                                        <td><a href="{{ route('form', ['form' => $form->id]) }}">View</a></td>
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
