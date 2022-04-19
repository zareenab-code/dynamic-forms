@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header  d-flex justify-content-between">
                        <span>Create Form</span>
                        <a class="btn btn-sm btn-primary pull-right" href="javascript:void(0)" onclick="addInput();">Add
                            Input</a>

                    </div>

                    <div class="card-body">
                        <div>
                            <p class="text-danger" id="error_msg"></p>
                        </div>
                        <form id="create_form" method="POST" action="{{ route('admin.form.create') }}">

                            <div class="form-group">
                                <label for="">Form Title</label>
                                <input class="form-control" placeholder="Form title" name="form_title" required />
                            </div>
                            <table class="table table-bordered" id="input-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Label</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Only Alpha</th>
                                        <th>Alpha Numeric</th>
                                        <th>Only Numbers</th>
                                        <th>Decimal Numbers</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="name[]" placeholder="Input name" value=""
                                                class="form-control" required />
                                        </td>
                                        <td>
                                            <input type="text" name="label[]" placeholder="Input label" value=""
                                                class="form-control" required />
                                        </td>
                                        <td>
                                            <select class="form-control" name="type[]" required>
                                                <option value="">-Select Type-</option>
                                                <option value="text">Text</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="checkbox">Checkbox</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='hidden' value='0' name='required[]' class="req_hid">
                                            <input type="checkbox" name="required-check[]" class="req_chk" />
                                        </td>
                                        <td>
                                            <input type="hidden" name="alpha[]" class="alpha_hid" value="0" />
                                            <input type="checkbox" name="alpha-check[]" class="alpha_chk" />
                                        </td>
                                        <td>
                                            <input type="hidden" value="0" name="alpha_numeric[]" class="alpha_num_hid">
                                            <input type="checkbox" name="alpha_numeric_check[]" class="alpha_num_chk" />
                                        </td>
                                        <td>
                                            <input type="hidden" value="0" name="numbers[]" class="num_hid">
                                            <input type="checkbox" name="numbers_check[]" class="num_chk" />
                                        </td>
                                        <td>
                                            <input type="hidden" value="0" name="decimal_numbers[]" class="decimal_num_hid">
                                            <input type="checkbox" name="decimal_numbers_check[]" class="decimal_num_chk" />
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-danger delete_row" href="javascript:void(0)">Delete</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="submit" name="form_submit" class="btn btn-primary" value="Submit" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {});

        function addInput() {
            var tbody = $('#input-table').children('tbody');

            var row = '<tr>';

            row += '<td>';
            row += '<input type="text" name="name[]" placeholder="Input name" value="" class = "form-control" required / >';
            row += '</td>';

            row += '<td>';
            row += '<input type="text" name="label[]" placeholder="Input label" value="" class="form-control" required />';
            row += '</td>';

            row += '<td>';
            row += '<select class="form-control" name="type[]" required >';
            row += '<option value="">-Select Type-</option>';
            row += '<option value="text">Text</option>';
            row += '<option value="textarea">Textarea</option>';
            row += '<option value="checkbox">Checkbox</option>';
            row += '</select>';
            row += '</td>';

            row += '<td>';
            row += '<input type="hidden" value="0" name="required[]" class="req_hid" >';
            row += '<input type="checkbox" name="required-check[]"  class="req_chk" />';
            row += '</td>';

            row += '<td>';
            row += '<input type="hidden" value="0" name="alpha[]" class="alpha_hid">';
            row += '<input type="checkbox" name="alpha-check[]" class="alpha_chk" />';
            row += '</td>';

            row += '<td>';
            row += '<input type="hidden" value="0" name="alpha_numeric[]" class="   alpha_num_hid">';
            row += '<input type="checkbox" name="alpha_numeric_check[]" class="alpha_num_chk" />';
            row += '</td>';

            row += '<td>';
            row += '<input type="hidden" value="0" name="numbers[]" class="    num_hid">';
            row += '<input type="checkbox" name="numbers_check[]" class="num_chk" />';
            row += '</td>';

            row += '<td>';
            row += '<input type="hidden" value="0" name="decimal_numbers[]" class="decimal_num_hid">';
            row += '<input type="checkbox" name="decimal_numbers_check[]" class="decimal_num_chk" />';
            row += '</td>';

            row += '<td>';
            row += '<a class="btn btn-sm btn-danger delete_row" href="javascript:void(0)">Delete</a>';
            row += '</td>';

            row += '</tr>';
            tbody.append(row);

        }

        $(document).on('change', '.req_chk', function() {
            if ((this).checked) {
                $(this).closest('td').find('.req_hid').val("1");
            } else {
                $(this).closest('td').find('.req_hid').val("0");
            }
        });

        $(document).on('change', '.alpha_chk', function() {
            if ((this).checked) {
                $(this).closest('td').find('.alpha_hid').val("1");
            } else {
                $(this).closest('td').find('.alpha_hid').val("0");
            }
        });

        $(document).on('change', '.alpha_num_chk', function() {
            if ((this).checked) {
                $(this).closest('td').find('.alpha_num_hid').val("1");
            } else {
                $(this).closest('td').find('.alpha_num_hid').val("0");
            }
        });

        $(document).on('change', '.num_chk', function() {
            if ((this).checked) {
                $(this).closest('td').find('.num_hid').val("1");
            } else {
                $(this).closest('td').find('.num_hid').val("0");
            }
        });

        $(document).on('change', '.decimal_num_chk', function() {
            if ((this).checked) {
                $(this).closest('td').find('.decimal_num_hid').val("1");
            } else {
                $(this).closest('td').find('.decimal_num_hid').val("0");
            }
        });

        $(document).on('click', '.delete_row', function() {
            (this).closest('tr').remove();
        });

        $('#create_form').submit(function(event) {
            event.preventDefault();
            var inputFieldCount = $('#input-table >tbody >tr').length;
            if (inputFieldCount == 0) {
                alert('Please add atleast one input.');
                return false;
            }
            $("#error_msg").text('');
            const url = event.target.action;
            const formData = $('#create_form').serialize();
            $.ajax({
                type: "post",
                url: url,
                data: formData,
                dataType: 'json',
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message)
                        location.reload();
                    } else {
                        $("#error_msg").text(response.error);
                    }
                }
            });
            return false;
        });
    </script>
@endsection
