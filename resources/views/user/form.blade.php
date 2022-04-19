@extends('layouts.frontend')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $form->title }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('form', ['form' => $form->id]) }}">
                            @csrf
                            @foreach ($formFields as $formField)
                                @php
                                    $inputFieldId = $formField->name . '_field_id';
                                @endphp
                                <input type="hidden" name="{{ $inputFieldId }}" value="{{ $formField->id }}" />
                                @if ($formField->type == 'textarea')
                                    <div class="mb-3">
                                        <label for="{{ $formField->name }}"
                                            class="form-label">{{ $formField->label }}</label>
                                        <textarea class="form-control" id="{{ $formField->name }}" name="{{ $formField->name }}"></textarea>
                                        <div class="form-text text-danger">
                                        </div>
                                    </div>
                                @elseif($formField->type == 'checkbox')
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="{{ $formField->name }}"
                                            name="{{ $formField->name }}" value="1">
                                        <label class="form-check-label"
                                            for="{{ $formField->name }}">{{ $formField->label }}</label>
                                        <div class="form-text text-danger">
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label for="{{ $formField->name }}"
                                            class="form-label">{{ $formField->label }}</label>
                                        <input type="{{ $formField->type }}" class="form-control"
                                            id="{{ $formField->name }}" name="{{ $formField->name }}">
                                        <div class="form-text text-danger">
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
