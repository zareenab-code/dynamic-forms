@extends('layouts.app')

@section('content')
    <div class="container-fluid">
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
                                        @foreach ($formData as $data)
                                            <tr>
                                                <td>{{ $index }}</td>
                                                @for ($i = 0; $i < count($formFields); $i++)
                                                    @php $name=$formFields[$i]->name; @endphp
                                                    <td>
                                                        @if ($formFields[$i]->type == 'checkbox')
                                                            {{ $data->$name ? 'Yes' : 'No' }}
                                                        @elseif($formFields[$i]->type == 'date' && !empty($data->$name))
                                                            {{ date('d-m-Y', strtotime($data->$name)) }}
                                                        @else
                                                            {{ $data->$name }}
                                                        @endif

                                                    </td>
                                                @endfor
                                                @php $index++; @endphp
                                            </tr>
                                        @endforeach
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
