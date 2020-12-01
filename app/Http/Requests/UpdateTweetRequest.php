<?php

namespace App\Http\Requests;

class UpdateTweetRequest extends MainRequest
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
            'twitter_text' => 'required|min:10|max:80',
        ];
    }
}
