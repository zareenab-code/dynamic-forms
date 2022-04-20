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

    public function getForm(Form $form)
    {
        $formOpened = is_null($form->opened) ? 0 : $form->opened;
        $form->opened = $formOpened + 1;
        $form->save();
        $formFields = FormField::where('form_id', $form->id)->get();

        return view('user.form', ['form' => $form, 'formFields' => $formFields]);
    }

    public function postForm(Request $request, Form $form)
    {
        $formFields = FormField::where('form_id', $form->id)->get();
        $validationRules = [];
        foreach ($formFields as $formField) {
            $validationRules[$formField->name] = $formField->validations;
        }
        $request->validate($validationRules);

        foreach ($formFields as $formField) {
            $input = $formField->name;
            $inputValue = $request->$input;

            $formValue = new FormValue();
            $formValue->form_field_id = $formField->id;

            if ($formField->type == "textarea") {
                $formValue->textarea_value = $inputValue;
            } else if ($formField->type == "checkbox") {
                $checkboxValue = (isset($inputValue)) ? 1 : 0;
                $formValue->checkbox_value = $checkboxValue;
            } else {
                $formValue->value = $inputValue;
            }
            $formValue->save();
        }
        $formSubmitted = is_null($form->submitted) ? 0 : $form->submitted;
        $form->submitted = $formSubmitted + 1;
        $form->save();
        return redirect()->to('/')->with('message', 'Form submitted successfully.');
    }

    public function getFormData(Form $form)
    {
        $formFields = FormField::where('form_id', $form->id)->get();
        $formData = DB::table('form_fields AS ff')
            ->join('form_values AS fv', 'ff.id', '=', 'fv.form_field_id')
            ->select('ff.name', DB::raw("CASE WHEN ff.type = 'textarea' THEN fv.textarea_value WHEN ff.type = 'checkbox' THEN fv.checkbox_value ELSE fv.value END AS input_value"))
            ->where('ff.form_id', $form->id)
            ->orderBy('fv.created_at', 'DESC')
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
        $name = $request->name;
        $label = $request->label;
        $type = $request->type;
        $min = $request->min;
        $max = $request->max;
        $options = $request->options;
        $required = $request->required;
        $alpha = $request->alpha;
        $alpha_numeric = $request->alpha_numeric;
        $numbers = $request->numbers;
        $decimal_numbers = $request->decimal_numbers;

        $form_exist = Form::where('title', $form_title)->first();

        if (!empty($form_exist)) {
            return json_encode(['success' => false, 'error' => 'Form already exist.']);
        }

        $form = new Form();
        $form->title = $form_title;
        $form->save();


        foreach ($name as $key => $value) {

            $validation = '';
            if ($required[$key]) {
                $validation .= 'required|';
            }

            if ($alpha[$key]) {
                $validation .= 'regex:/^[a-zA-Z\s]*$/|';
            }

            if ($alpha_numeric[$key]) {
                $validation .= 'regex:/^[a-zA-Z0-9\s]*$/|';
            }

            if ($numbers[$key]) {
                $validation .= 'integer|';
            }

            if ($decimal_numbers[$key]) {
                $validation .= 'numeric|';
            }

            if ($type[$key] == 'email') {
                $validation .= 'email:rfc,dns|';
            }

            if (($type[$key] == 'text' || $type[$key] == 'textarea') && !empty($min[$key])) {
                $validation .= 'min:' . $min[$key] . '|';
            }

            if (($type[$key] == 'text' || $type[$key] == 'textarea') && !empty($max[$key])) {
                $validation .= 'max:' . $max[$key] . '|';
            }

            $validation = rtrim($validation, '|');

            $formField = new FormField();
            $formField->form_id = $form->id;
            $formField->name = strtolower(str_replace(" ", "_", $value));
            $formField->label =  $label[$key];
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
        return json_encode(['success' => true, 'message' => "Form created successfully."]);
    }
}
