<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ClosedPostRequest extends FormRequest
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
        $validation = [
            'closed_type' => 'required',
        ];
        if ($this->input('create_type') == config('const.CLOSED_CREATE_TYPE.WEEK')) {
          $validation['closed_week'] = 'required';
        }

        if ($this->input('create_type') == config('const.CLOSED_CREATE_TYPE.DAY')) {
          $validation['closed_day'] = 'filled|unique:closed|date_format:Y-m-d';
        }
        return $validation;
        
    }
    public function messages() {
      return [
          'required' => ':attributeを必ず入力してください。',
          'unique' => 'この:attributeは既に登録されています。',
          'date_format' => '休診日を正しく入力してください。'
      ];
  }

  public function attributes() {
      return [
          'closed_day' => '休診日',
          'closed_type' => '休診区分',
          'closed_week' => '曜日',
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
        $date = $this->input('closed_day');
        return $url->route('closed.index', ['month' => Carbon::parse($date)->format('Y-m')]);
    }
}
