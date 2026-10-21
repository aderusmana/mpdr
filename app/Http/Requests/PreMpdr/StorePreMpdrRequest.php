<?php

namespace App\Http\Requests\PreMpdr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreMpdrRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['channel', 'certification'] as $field) {
            $value = $this->input($field);
            if (is_string($value)) {
                $this->merge([$field => array_values(array_filter(array_map('trim', explode(',', $value))))]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'form_status' => 'required|string|in:Draft,Submit',
            'no_reg' => 'required|string|unique:pre_mpdr_forms,no',
            'projectName' => 'required|string|max:255',
            'levelPriority' => 'required',
            'brandName' => 'required',
            'rationalForDevelopment' => 'required',
            'productCategory' => 'required',
            'productCategoryText' => 'nullable|string',
            'channel' => 'required|array|min:1',
            'channel.*' => 'required|string|in:International,Regional,Industrial,FSBC-Direct,FSBC-Distributor',
            'country' => 'nullable|string',
            'productDescription' => 'required',
            'usageDescription' => 'required|string',
            'storageTemperature' => 'required|string',
            'deliveryTemperature' => 'required|string',
            'certification' => 'required|array|min:1',
            'certification.*' => 'required|string|in:BPOM,HALAL,Others',
            'certificationText' => 'nullable|string',
            'weightProduct' => 'required|string',
            'packaging' => 'required|string|in:Existing,New',
            'ExistingPackagingText' => ['nullable', 'string', Rule::requiredIf($this->input('packaging') === 'Existing')],
            'NewPackagingText' => ['nullable', 'string', Rule::requiredIf($this->input('packaging') === 'New')],
            'productVariation' => 'required|string',
            'potentialVolume' => 'required|string',
            'expectedMargin' => 'required',
            'priceEstimate' => 'required',
            'targetLaunchText' => 'required',
            'competitor_names.*' => 'nullable|string|max:255',
            'competitor_sizes.*' => 'nullable|string|max:255',
            'competitor_packagings.*' => 'nullable|string|max:255',
            'competitor_prices.*' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $channel = (array) $this->input('channel', []);
            if (in_array('International', $channel, true) && blank($this->input('country'))) {
                $validator->errors()->add('country', 'Country is required when International channel is selected.');
            }

            $certification = (array) $this->input('certification', []);
            if (in_array('Others', $certification, true) && blank($this->input('certificationText'))) {
                $validator->errors()->add('certificationText', 'Please specify the other certification.');
            }
        });
    }
}
