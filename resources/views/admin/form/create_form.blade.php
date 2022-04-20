@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .table td,
        .table th {
            min-width: 150px;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header  d-flex justify-content-between">
                        <span>Create Form</span>
                    </div>

                    <div class="card-body">
                        <div>
                            <p class="text-danger" id="error_msg"></p>
                        </div>

                        <form id="create_form" method="POST" action="{{ route('admin.form.create') }}">

                            <div class="mb-3">
                                <label for="form_title" class="form-label">Form Title</label>
                                <input class="form-control" placeholder="Form title" name="form_title" required />
                            </div>
                            <div class="mb-2 clearfix "><a class="btn btn-sm btn-success float-end"
                                    href="javascript:void(0)" onclick="addInput();">Add
                                    Input</a></div>
                            <div class="table-responsive">

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
                                            <th>Min</th>
                                            <th>Max</th>
                                            <th>Options</th>
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
                                                <select class="form-control input_type" name="type[]" required>
                                                    <option value="">-Select Type-</option>
                                                    <option value="text">Text</option>
                                                    <option value="email">Email</option>
                                                    <option value="date">Date</option>
                                                    <option value="textarea">Textarea</option>
                                                    <option value="checkbox">Checkbox</option>
                                                    <option value="dropdown">Dropdown</option>
                                                    <option value="radio">Radio</option>
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
                                                <input type="hidden" value="0" name="alpha_numeric[]"
                                                    class="alpha_num_hid">
                                                <input type="checkbox" name="alpha_numeric_check[]"
                                                    class="alpha_num_chk" />
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="numbers[]" class="num_hid">
                                                <input type="checkbox" name="numbers_check[]" class="num_chk" />
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="decimal_numbers[]"
                                                    class="decimal_num_hid">
                                                <input type="checkbox" name="decimal_numbers_check[]"
                                                    class="decimal_num_chk" />
                                            </td>

                                            <td>
                                                <input type="number" name="min[]" placeholder="Minimum"
                                                    class="min form-control" />
                                            </td>
                                            <td>
                                                <input type="number" name="max[]" placeholder="Maximum"
                                                    class="max form-control" />
                                            </td>
                                            <td>
                                                <input class="chosenOptions" type="hidden" name="options[]" value="" />
                                                <div>
                                                    <p class="chosenOptionsStr"></p>
                                                    <a style="display: none" href="javascript:void(0)"
                                                        class="optEditLink">Edit</a>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-danger delete_row"
                                                    href="javascript:void(0)">Delete</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="submit" name="form_submit" class="btn btn-primary mt-3" value="Submit" />
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="optionsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionsTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="optForm">
                    <div class="modal-body">
                        <div class="text-center">
                            <p id="optErr" class="text-danger"></p>
                        </div>

                        <input type="hidden" id="rowIndex" value="" />
                        <div class="clearfix">
                            <button class="btn btn-success btn-sm float-end mb-2 " id="addOptionBtn"
                                onclick="addOption(event);">Add</button>
                        </div>
                        <div class="mb-3">
                            <table class="table" id="optionsTable">
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="cancelOptions" type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button id="saveOptions" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#optionsModal').on('hidden.bs.modal', function() {
                $("#optErr").text('');
                $('#optionsTable >tbody').empty();
            });

        });

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
            row += '<select class="form-control input_type" name="type[]" required >';
            row += '<option value="">-Select Type-</option>';
            row += '<option value="text">Text</option>';
            row += '<option value="email">Email</option>';
            row += '<option value="date">Date</option>';
            row += '<option value="textarea">Textarea</option>';
            row += '<option value="checkbox">Checkbox</option>';
            row += '<option value="dropdown">Dropdown</option>';
            row += '<option value="radio">Radio</option>';
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
            row += '<input type="number" name="min[]" placeholder="Minimum" class="min form-control" />';
            row += '</td>';

            row += '<td>';
            row += '<input type="number" name="max[]" placeholder="Maximum" class="max form-control" />';
            row += '</td>';

            row += '<td>';
            row += '<input class="chosenOptions" type="hidden" name="options[]" value="" />';
            row += '<div>';
            row += '<p class="chosenOptionsStr"></p>';
            row += '<a style="display: none" href="javascript:void(0)" class="optEditLink">Edit</a>';
            row += '</div>';
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

        $(document).on('change', '.input_type', function() {
            var value = $(this).val();
            var rowIndex = $(this).closest('tr').index();
            var minInput = $(this).closest('tr').find('.min');
            var maxInput = $(this).closest('tr').find('.max');

            if (value == 'dropdown' || value == 'radio') {
                openAddOptions(rowIndex);
            } else if (value == 'text') {
                minInput.show();
                maxInput.show();

                maxInput.attr('max', '1000');
                // minInput.attr("type", 'date');
                // maxInput.attr("type", 'date');
            } else if (value == 'textarea') {
                minInput.show();
                maxInput.show();
                maxInput.removeAttr('max');
            } else {
                // minInput.attr("type", 'number');
                // maxInput.attr("type", 'number');
                minInput.hide();
                maxInput.hide();
                $(this).closest('tr').find('.chosenOptions').val('');
                $(this).closest('tr').find('.chosenOptionsStr').text('');
                $(this).closest('tr').find('.optEditLink').hide();
            }
        });

        $(document).on('click', '.delete_row', function() {
            (this).closest('tr').remove();
        });

        function openAddOptions(rowIndex) {
            $("#optionsTitle").text('Add Options');
            addOption();
            $("#rowIndex").val(rowIndex);
            $("#cancelOptions").attr("onclick", "cancelAddOptions(" + rowIndex + ")");
            $('#optionsModal').modal('show');
        }

        function cancelAddOptions(rowIndex) {
            var row = $('#input-table >tbody >tr').eq(rowIndex);
            row.find('.input_type').val('');
        }

        function addOption(event = null) {
            if (event) {
                event.preventDefault();
            }

            var row = '';
            row += '<tr>';
            row += '<td><input type="text" name="opt[]" placeholder="Option" required class="form-control"></td>';
            row += '<td><button class="btn btn-danger btn-sm delete-option">Delete</button></td>';
            row += '</tr>';
            $('#optionsTable').append(row);
        }

        $(document).on('click', '.delete-option', function() {
            (this).closest('tr').remove();
        });

        $(document).on('click', '.optEditLink', function() {
            var rowIndex = $(this).closest('tr').index();
            $("#optionsTitle").text('Edit Options');
            $("#rowIndex").val(rowIndex);
            $("#cancelOptions").attr("onclick", "cancelUpdateOptions()");
            var optionsStr = $(this).closest('tr').find('.chosenOptions').val();
            var optionsArray = optionsStr.split(",");

            $.each(optionsArray, function(index, val) {
                var row = '';
                row += '<tr>';
                row +=
                    '<td><input type="text" name="opt[]" placeholder="Option" required class="form-control" value="' +
                    val + '"></td>';
                row += '<td><button class="btn btn-danger btn-sm delete-option">Delete</button></td>';
                row += '</tr>';
                $('#optionsTable').append(row);
            });

            $('#optionsModal').modal('show');
        });

        function cancelUpdateOptions() {
            $('#optionsModal').modal('hide');
        }

        $("#optForm").submit(function(event) {
            event.preventDefault();
            $("#optErr").text('');
            var rowIndex = $("#rowIndex").val();
            var optionCount = $('#optionsTable >tbody >tr').length;
            if (optionCount == 0) {
                $("#optErr").text('Please add atleast one option.');
                // alert("Please add atleast one option.");
                return false;
            }
            var optString = '';
            var options = $("input[name='opt[]']")
                .map(function() {
                    optString += titleCase($(this).val()) + ',';
                    return titleCase($(this).val());
                }).get();

            optString = optString.slice(0, -1);

            var unique = options.filter(function(item, pos) {
                return options.indexOf(item) != pos;
            });

            if (unique.length != 0) {
                $("#optErr").text('Options should have unique values.');
                return false;
            }

            var row = $('#input-table >tbody >tr').eq(rowIndex);
            row.find('.chosenOptions').val(optString);
            row.find('.chosenOptionsStr').text(optString);
            row.find('.optEditLink').show();

            $('#optionsModal').modal('hide');
        });

        $('#create_form').submit(function(event) {
            event.preventDefault();
            $("#error_msg").text('');
            var inputFieldCount = $('#input-table >tbody >tr').length;
            if (inputFieldCount == 0) {
                $("#error_msg").text('Please add atleast one input.');
                // alert('Please add atleast one input.');
                return false;
            }

            var names = $("input[name='name[]']")
                .map(function() {
                    return $.trim($(this).val().toLowerCase());
                }).get();

            var labels = $("input[name='label[]']")
                .map(function() {
                    return $.trim($(this).val().toLowerCase());
                }).get();

            var duplicateNames = names.filter(function(item, pos) {
                return names.indexOf(item) != pos;
            });

            if (duplicateNames.length != 0) {
                $("#error_msg").text('Names should be unique.');
                return false;
            }

            var duplicateLabels = labels.filter(function(item, pos) {
                return labels.indexOf(item) != pos;
            });

            if (duplicateLabels.length != 0) {
                $("#error_msg").text('Labels should be unique.');
                return false;
            }

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
                        // alert(response.message)
                        // location.reload();
                        window.location.href = "{{ route('admin.home') }}";
                    } else {
                        $("#error_msg").text(response.error);
                    }
                }
            });
            return false;
        });

        function titleCase(str) {
            var wordsArray = str.toLowerCase().split(/\s+/);
            var upperCased = wordsArray.map(function(word) {
                return word.charAt(0).toUpperCase() + word.substr(1);
            });
            return upperCased.join(" ");
        }
    </script>
@endsection
