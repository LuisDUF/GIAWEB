<?php

namespace App\Http\Requests;

use App\Models\WorkoutTag;
use Illuminate\Foundation\Http\FormRequest;

class CreateWorkoutTagRequest extends FormRequest
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
        return WorkoutTag::$rules;
    }
}
