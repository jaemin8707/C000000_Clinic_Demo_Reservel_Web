<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'patient_no' => 'nullable|max:255',
            'name' => 'nullable|max:255',
            'email' => 'nullable|email',
            'tel' => 'nullable|digits_between:8,11',
            'pet_type' => 'nullable|max:255',
            'pet_name' => 'nullable|max:255',
            'pet_symptom' => 'nullable|max:255',
        ];
    }
    public function messages() {
        return [
            'max' => ':attributeを:max文字以下入力してください。',
            'email' => 'メールアドレスを正しい形式に入力してください。',
            'tel.digits_between' => ':attributeを半角数字を:min桁以上、:max桁以下を入力してください。',
        ];
    }

    public function attributes() {
        return [
            'patient_no' => '診察券番号',
            'name' => '名前',
            'email' => 'メールアドレス',
            'tel' => '電話番号',
            'pet_type' => 'ペットの種類',
            'pet_name' => 'ペットの名前',
            'pet_symptom' => '症状など'
        ];
    }
    
    /**
    * Get the URL to redirect to on a validation error.
    *
    * @return string
    */
    protected function getRedirectUrl()
    {
        $url = $this->redirector->getUrlGenerator();

        $id = $this->input('id');

        return $url->route('reserve.edit', ['reserve' => $id]);
    }
}
