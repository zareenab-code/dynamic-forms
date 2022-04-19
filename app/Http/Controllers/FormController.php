<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Form;
use App\Models\FormField;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::latest()->get();
        return view('user.forms', ['forms' => $forms]);
    }

    public function getForm(Form $form)
    {
        $formFields = FormField::where('form_id', $form->id)->get();

        return view('user.form', ['form' => $form, 'formFields' => $formFields]);
    }

    public function postForm(Request $request, Form $form)
    {
        return 'submitted';
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
                $validation .= 'numeric';
            }

            $validation = rtrim($validation, '|');

            $formField = new FormField();
            $formField->form_id = $form->id;
            $formField->name = strtolower(str_replace(" ", "_", $value));
            $formField->label =  $label[$key];
            $formField->type =  $type[$key];
            $formField->validations = $validation;
            $formField->save();
        }
        return json_encode(['success' => true, 'message' => "Form created successfully."]);
    }
}
