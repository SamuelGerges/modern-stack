<?php

namespace App\Http\Requests\API\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Modify input before validation.
     */
    protected function prepareForValidation(): void
    {
        $dueDate = null;
        $status  = null;

        if ($this->filled('due_date')) {
            try {

                $dueDate = (new \DateTime($this->due_date))->format('Y-m-d');
            } catch (\Exception $e) {
                $dueDate = $this->due_date;
            }
        }


        $status = $this->input('status', 'pending');

        $this->merge([
            'user_id'  => auth()->id(),
            'due_date' => $dueDate,
            'status'   => $status,
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'status'      => 'nullable|in:pending,done',
            'user_id'     => 'required|exists:users,id',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required'         => 'The task title is required.',
            'title.max'              => 'The title may not be greater than 255 characters.',
            'due_date.date'          => 'The due date must be a valid date.',
            'due_date.after_or_equal'=> 'The due date cannot be in the past.',
            'status.in'              => 'Status must be either pending or done.',
        ];
    }
}
