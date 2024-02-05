<?php

namespace App\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateRoleRequest extends FormRequest
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
        if($this->getMethod() === 'PUT' || $this->getMethod() ===  'PATCH'){

            return [
                'nickname_en'         => 'required|string|max:191|unique:roles,nickname_en,' . $this->id,
                'permissions'         => 'required|array',
            ];
        }else{
            
            return [
                'nickname_en'         => 'required|string|max:191|unique:roles,nickname_en',
                'permissions'         => 'required|array',
            ];
        }
    }
}
