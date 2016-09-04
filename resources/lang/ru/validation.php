<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute должен быть подтвержден.',
    'active_url'           => ':attribute не является корректным URL.',
    'after'                => ':attribute должен быть не ранее :date.',
    'alpha'                => ':attribute может содержать только буквы.',
    'alpha_dash'           => ':attribute может содержать только буквы, цифры, и тире.',
    'alpha_num'            => ':attribute может содержать только буквы и цифры.',
    'array'                => ':attribute должен быть массивом.',
    'before'               => ':attribute должен быть ранее :date.',
    'between'              => [
        'numeric' => ':attribute должен находиться в границах: :min и :max.',
        'file'    => ':attribute должен быть размером от :min , до :max килобайт.',
        'string'  => ':attribute должен содержать от :min , до :max знаков.',
        'array'   => ':attribute должен содержать от :min , до :max значений.',
    ],
    'boolean'              => ':attribute должен быть равен true или false.',
    'confirmed'            => ':attribute подтверждение не соответствует.',
    'date'                 => ':attribute не является корректной датой.',
    'date_format'          => ':attribute не соответстует формату :format.',
    'different'            => ':attribute и :other должны отличаться.',
    'digits'               => ':attribute должен быть :digits .',
    'digits_between'       => ':attribute должен быть в границах от :min , до :max цифр.',
    'dimensions'           => ':attribute имеет некорректное разрешение изображения.',
    'distinct'             => ':attribute поле имеет дублирующиеся значение.',
    'email'                => ':attribute должен быть корректной электронной почтой.',
    'exists'               => 'Выбранный :attribute неправильный.',
    'file'                 => ':attribute Должен быть файлом.',
    'filled'               => ':attribute поле является обязательным.',
    'image'                => ':attribute должен быть изображением.',
    'in'                   => 'Выбранный :attribute некорректен.',
    'in_array'             => ':attribute поле не существует в :other.',
    'integer'              => ':attribute должен быть целочисленным.',
    'ip'                   => ':attribute должен быть корректным IP-адресом.',
    'json'                 => ':attribute должен быть правильной JSON-строкой.',
    'max'                  => [
        'numeric' => ':attribute должен быть более, чем :max.',
        'file'    => ':attribute должен быть более, чем :max килобайт.',
        'string'  => ':attribute должен содержать более, чем :max знаков.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
