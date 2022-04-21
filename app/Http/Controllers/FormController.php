<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormValue;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::latest()->get();
        return view('user.forms', ['forms' => $forms]);
    }

    public function openForm(Form $form)
    {
        $formOpened = is_null($form->opened) ? 0 : $form->opened;
        $form->opened = $formOpened + 1;
        $form->save();
        return redirect()->route('form', ['form' => $form]);
    }

    public function getForm(Form $form)
    {
        // $formOpened = is_null($form->opened) ? 0 : $form->opened;
        // $form->opened = $formOpened + 1;
        // $form->save();
        $formFields = FormField::where('form_id', $form->id)->get();

        return view('user.form', ['form' => $form, 'formFields' => $formFields]);
    }

    public function postForm(Request $request, Form $form)
    {
        $table_name = $form->data_table_name;
        $formFields = FormField::where('form_id', $form->id)->get();
        $validationRules = [];
        foreach ($formFields as $formField) {
            $validationRules[$formField->name] = $formField->validations;
        }
        $request->validate($validationRules);

        $data = [];
        foreach ($formFields as $formField) {
            $input = $formField->name;
            $data[$input] = $request->$input;
        }
        DB::table($table_name)->insert($data);
        $formSubmitted = is_null($form->submitted) ? 0 : $form->submitted;
        $form->submitted = $formSubmitted + 1;
        $form->save();
        return redirect()->to('/')->with('message', 'Form submitted successfully.');
    }

    public function getFormData(Form $form)
    {
        $formFields = FormField::where('form_id', $form->id)->get();
        $table_name = $form->data_table_name;
        $formData = DB::table($table_name)
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.form.form_data', ['formTitle' => $form->title, 'formFields' => $formFields, 'formData' => $formData]);
    }

    public function getCreateForm()
    {
        return view('admin.form.create_form');
    }

    public function postCreateForm(Request $request)
    {
        $form_title = trim($request->form_title);
        $table_name = strtolower(str_replace(" ", "_", $form_title));

        // $alpha = $request->alpha;
        // $alpha_numeric = $request->alpha_numeric;
        // $numbers = $request->numbers;
        // $decimal_numbers = $request->decimal_numbers;

        // $form_exist = Form::where('title', $form_title)->first();

        // if (!empty($form_exist)) {
        //     return json_encode(['success' => false, 'error' => 'Form already exist.']);
        // }

        if (Schema::hasTable($table_name)) {
            return json_encode(['success' => false, 'error' => 'Form already exist.']);
        }
        try {

            $form = new Form();
            $form->title = ucwords($form_title);
            $form->data_table_name = $table_name;
            $form->save();

            Schema::create($table_name, function (Blueprint $table) use ($request, $form) {
                $name = $request->name;
                $label = $request->label;
                $type = $request->type;
                $min = $request->min;
                $max = $request->max;
                $options = $request->options;
                $required = $request->required;
                $validation_rule = $request->validation_rule;
                $table->id();
                foreach ($name as $key => $value) {
                    if ($type[$key] == 'text') {
                        if (!empty($validation_rule[$key]) && $validation_rule[$key] == 'decimal_numbers') {
                            $table->decimal($name[$key], 15, 2)->nullable();
                        } elseif (!empty($validation_rule[$key]) && $validation_rule[$key] == 'numbers') {
                            $table->bigInteger($name[$key])->nullable();
                        } else {
                            if (!empty($max[$key])) {
                                $table->string($name[$key], $max[$key])->nullable();
                            } else {
                                $table->string($name[$key], 255)->nullable();
                            }
                        }
                    } else if ($type[$key] == 'textarea') {
                        if (!empty($max[$key])) {
                            $table->string($name[$key], $max[$key])->nullable();
                        } else {
                            $table->text($name[$key])->nullable();
                        }
                    } else if ($type[$key] == 'date') {
                        $table->date($name[$key])->nullable();
                    } else if ($type[$key] == 'checkbox') {
                        $table->boolean($name[$key])->nullable();
                    } else {
                        $table->string($name[$key], 255)->nullable();
                    }


                    //validation
                    $validation = '';
                    if ($required[$key]) {
                        $validation .= 'required|';
                    } else {
                        $validation .= 'nullable|';
                    }

                    if ($validation_rule[$key] == 'alpha') {
                        $validation .= 'regex:/^[a-zA-Z\s]*$/|';
                    }

                    if ($validation_rule[$key] == 'alpha_numeric') {
                        $validation .= 'regex:/^[a-zA-Z0-9\s]*$/|';
                    }

                    if ($validation_rule[$key] == 'numbers') {
                        if (!empty($min[$key]) && !empty($max[$key])) {
                            $validation .= 'regex:/^[0-9]{' . $min[$key] . ',' . $max[$key] . '}$/|';
                        } else if (!empty($min[$key]) && empty($max[$key])) {
                            $validation .= 'regex:/^[0-9]{' . $min[$key] . ',' . '}$/|';
                        } else if (empty($min[$key]) && !empty($max[$key])) {
                            $validation .= 'regex:/^[0-9]{' . $max[$key] . '}$/|';
                        } else {
                            $validation .= 'regex:/^[0-9]*$/|';
                        }
                    }

                    if ($validation_rule[$key] == 'integers') {
                        $validation .= 'integer|';
                    }

                    if ($validation_rule[$key] == 'decimal_numbers') {
                        $validation .= 'regex:/^\d*\.?\d{1,2}$/|numeric|';
                    }

                    if ($type[$key] == 'email') {
                        $validation .= 'email|';
                    }

                    if ($validation_rule[$key] != 'numbers') {
                        if (($type[$key] == 'text' || $type[$key] == 'textarea') && !empty($min[$key])) {
                            $validation .= 'min:' . $min[$key] . '|';
                        }

                        if (($type[$key] == 'text' || $type[$key] == 'textarea') && !empty($max[$key])) {
                            $validation .= 'max:' . $max[$key] . '|';
                        }
                    }

                    $validation = rtrim($validation, '|');

                    $formField = new FormField();
                    $formField->form_id = $form->id;
                    $formField->name = strtolower(str_replace(" ", "_", $value));
                    $formField->label = ucfirst($label[$key]);
                    $formField->type =  $type[$key];
                    $formField->validations = $validation;

                    if ($type[$key] == "dropdown" && !empty($options[$key])) {
                        $formField->dropdown_options = $options[$key];
                    }

                    if ($type[$key] == "radio" && !empty($options[$key])) {
                        $formField->radio_options = $options[$key];
                    }

                    $formField->save();
                }
                $table->timestamps();
            });
            return json_encode(['success' => true, 'message' => "Form created successfully."]);
        } catch (\Exception $ex) {
            FormField::where('form_id', $form->id)->delete();
            Schema::dropIfExists($table_name);
            $form->delete();
            return json_encode(['success' => false, 'error' => 'Something went wrong.']);
        }
    }
}
