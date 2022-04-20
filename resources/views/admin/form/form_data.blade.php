@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>{{ $formTitle }}</span>

                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        @for ($i = 0; $i < count($formFields); $i++)
                                            <th>{{ strtoupper(str_replace('_', ' ', $formFields[$i]->name)) }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($formData) > 0)
                                        @php $index=1; @endphp
                                        @for ($j = 0; $j < count($formData) / count($formFields); $j++)
                                            <tr>
                                                <td>{{ $index }}</td>
                                                @for ($k = $j * count($formFields); $k < ($j + 1) * count($formFields); $k++)
                                                    <td>
                                                        {{ $formData[$k]->input_value }}
                                                    </td>
                                                @endfor
                                                @php $index++; @endphp
                                            </tr>
                                        @endfor
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="{{ count($formFields) + 1 }}">No Data Found
                                            </td>
                                        </tr>
                                    @endif



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
