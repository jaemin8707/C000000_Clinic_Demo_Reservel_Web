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
     * 予約リクエストに適用するバリデーションルールを取得
     *
     * @return array
     */
    public function rules()
    {
        $validation = [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'tel' => 'required|digits_between:8,11',
            'age' => 'required|max:255',
            'gender' => 'required',
            'pet_symptom' => 'max:255',
        ];
        if ($this->input('careType') == 2 || $this->input('careType') == 9) {
            
            $validation['patient_no'] = 'required';
        }
        return $validation;
    }
    public function messages() {
        return [
            'required' => ':attributeを必ず入力してください。',
            'pet_type.required'  => ':attributeを必ず選択してください。',
            'purpose.required'  => ':attributeを必ず選択してください。',
            'gender.required'  => ':attributeを必ず選択してください。',
            'max' => ':attributeを:max文字以下入力してください。',
            'email' => 'メールアドレスを正しい形式に入力してください。',
            'tel.digits_between' => ':attributeを半角数字を:min桁以上、:max桁以下を入力してください。',
        ];
    }

    public function attributes() {
        return [
            'name' => '名前',
            'email' => 'メールアドレス',
            'tel' => '電話番号',
            'pet_type' => 'ペットの種類',
            'pet_name' => 'ペットの名前',
            'pet_symptom' => '症状など',
            'purpose' => '来院目的',
            'patient_no' => '診察券番号',
            'age' => '年齢',
            'gender' => '性別'

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

        $post = $this->input('careType');

        return $url->route('reserve.create', $post);
    }
}
