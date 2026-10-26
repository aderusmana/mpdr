<?php

namespace App\Http\Requests\Mpdr;

use Illuminate\Foundation\Http\FormRequest;

class StoreMpdrRequest extends FormRequest
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
            'no_reg' => 'required|string',
            'no_pre' => 'required|string',
            'typeOfRequest' => 'required|string|in:New Product,Existing Product',
            'requester_id' => 'nullable|exists:users,id',
            'productName' => 'required|string|max:255',
            'levelPriority' => 'required',
            'initiator_name_display' => 'nullable|string',
            'initiator' => 'nullable|exists:users,nik',
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
            'competitor_names' => 'required|array|min:1',
            'competitor_names.*' => 'required|string|max:255',
            'competitor_sizes.*' => 'required|string|max:255',
            'competitor_packagings.*' => 'required|string|max:255',
            'competitor_prices.*' => 'nullable|string|max:255',
            'weightProduct' => 'required',
            'packaging' => 'required',
            'ExistingPackagingText' => 'nullable|string',
            'NewPackagingText' => 'nullable|string',
            'productVariation' => 'required',
            'potentialVolume' => 'required',
            'expectedMargin' => 'required',
            'priceEstimate' => 'required',
            'targetLaunchText' => 'required',
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
