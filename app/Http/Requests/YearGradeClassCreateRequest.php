<?php

namespace App\Http\Requests;

class YearGradeClassCreateRequest extends ApiFormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
          'year' => 'required',
          'organization_id' => 'required|numeric',
          'master_grade_id' => 'required|numeric',
          'master_class_id' => 'required|numeric',
          'Total_number_of_students' => 'required|numeric',
          'active_status' => 'required|numeric',
        ];
    }
}