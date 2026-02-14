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

    'accepted'             => 'يجب قبول :attribute.',
    'accepted_if'          => 'يجب قبول :attribute عندما يكون :other يساوي :value.',
    'active_url'           => ':attribute لا يُمثّل رابطًا صحيحًا.',
    'after'                => 'يجب على :attribute أن يكون تاريخًا بعد :date.',
    'after_or_equal'       => 'يجب على :attribute أن يكون تاريخًا مساويًا أو بعد :date.',
    'alpha'                => 'يجب أن يحتوي :attribute على حروف فقط.',
    'alpha_dash'           => 'يجب أن يحتوي :attribute على حروف، أرقام، شرطات، أو شرطات سفلية فقط.',
    'alpha_num'            => 'يجب أن يحتوي :attribute على حروف وأرقام فقط.',
    'array'                => 'يجب أن يكون :attribute مصفوفة.',
    'before'               => 'يجب على :attribute أن يكون تاريخًا قبل :date.',
    'before_or_equal'      => 'يجب على :attribute أن يكون تاريخًا مساويًا أو قبل :date.',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد حروف :attribute بين :min و :max.',
        'array'   => 'يجب أن يحتوي :attribute على عناصر بين :min و :max.',
    ],
    'boolean'              => 'يجب أن تكون قيمة :attribute إما true أو false.',
    'confirmed'            => 'تأكيد :attribute غير متطابق.',
    'current_password'     => 'كلمة المرور غير صحيحة.',
    'date'                 => ':attribute ليس تاريخًا صحيحًا.',
    'date_equals'          => 'يجب أن يكون :attribute تاريخًا مساويًا لـ :date.',
    'date_format'          => 'لا يتطابق :attribute مع الشكل :format.',
    'declined'             => 'يجب رفض :attribute.',
    'declined_if'          => 'يجب رفض :attribute عندما يكون :other يساوي :value.',
    'different'            => 'يجب أن يكون :attribute و :other مختلفين.',
    'digits'               => 'يجب أن يحتوي :attribute على :digits أرقام.',
    'digits_between'       => 'يجب أن يحتوي :attribute بين :min و :max أرقام.',
    'dimensions'           => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct'             => 'للحقل :attribute قيمة مُكرّرة.',
    'doesnt_end_with'      => 'قد لا ينتهي :attribute بأحد القيم التالية: :values.',
    'doesnt_start_with'    => 'قد لا يبدأ :attribute بأحد القيم التالية: :values.',
    'email'                => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح.',
    'ends_with'            => 'يجب أن ينتهي :attribute بأحد القيم التالية: :values.',
    'enum'                 => ':attribute المحدد غير صالح.',
    'exists'               => ':attribute المحدد غير صالح.',
    'file'                 => 'يجب أن يكون :attribute ملفًا.',
    'filled'               => 'يجب أن يحتوي :attribute على قيمة.',
    'gt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أكثر من :value حروف.',
        'array'   => 'يجب أن يحتوي :attribute على أكثر من :value عناصر.',
    ],
    'gte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute على الأقل :value حروف.',
        'array'   => 'يجب أن يحتوي :attribute على :value عناصر أو أكثر.',
    ],
    'image'                => 'يجب أن يكون :attribute صورة.',
    'in'                   => ':attribute المحدد غير صالح.',
    'in_array'             => ':attribute غير موجود في :other.',
    'integer'              => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'ip'                   => 'يجب أن يكون :attribute عنوان IP صحيح.',
    'ipv4'                 => 'يجب أن يكون :attribute عنوان IPv4 صحيح.',
    'ipv6'                 => 'يجب أن يكون :attribute عنوان IPv6 صحيح.',
    'json'                 => 'يجب أن يكون :attribute نص JSON صالح.',
    'lt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أصغر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أقل من :value حروف.',
        'array'   => 'يجب أن يحتوي :attribute على أقل من :value عناصر.',
    ],
    'lte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أصغر من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute على الأكثر :value حروف.',
        'array'   => 'يجب ألا يحتوي :attribute على أكثر من :value عناصر.',
    ],
    'mac_address'          => 'يجب أن يكون :attribute عنوان MAC صحيح.',
    'max'                  => [
        'numeric' => 'يجب ألا تزيد قيمة :attribute عن :max.',
        'file'    => 'يجب ألا يزيد حجم الملف :attribute عن :max كيلوبايت.',
        'string'  => 'يجب ألا يزيد طول النص :attribute عن :max حروف.',
        'array'   => 'يجب ألا يحتوي :attribute على أكثر من :max عناصر.',
    ],
    'mimes'                => 'يجب أن يكون :attribute ملف من نوع: :values.',
    'mimetypes'            => 'يجب أن يكون :attribute ملف من نوع: :values.',
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min.',
        'file'    => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute على الأقل :min حروف.',
        'array'   => 'يجب أن يحتوي :attribute على الأقل :min عناصر.',
    ],
    'multiple_of'          => 'يجب أن تكون قيمة :attribute من مضاعفات :value.',
    'not_in'               => ':attribute المحدد غير صالح.',
    'not_regex'            => 'صيغة :attribute غير صالحة.',
    'numeric'              => 'يجب أن يكون :attribute رقمًا.',
    'password'             => 'كلمة المرور غير صحيحة.',
    'present'              => 'يجب تقديم :attribute.',
    'prohibited'           => 'حقل :attribute محظور.',
    'prohibited_if'        => 'حقل :attribute محظور عندما يكون :other يساوي :value.',
    'prohibited_unless'    => 'حقل :attribute محظور ما لم يكن :other ضمن :values.',
    'prohibits'            => 'حقل :attribute يمنع وجود :other.',
    'regex'                => 'صيغة :attribute غير صالحة.',
    'required'             => 'حقل :attribute مطلوب.',
    'required_array_keys'  => 'يجب أن يحتوي الحقل :attribute على مفاتيح: :values.',
    'required_if'          => 'حقل :attribute مطلوب عندما يكون :other يساوي :value.',
    'required_unless'      => 'حقل :attribute مطلوب ما لم يكن :other ضمن :values.',
    'required_with'        => 'حقل :attribute مطلوب عندما تكون القيم :values موجودة.',
    'required_with_all'    => 'حقل :attribute مطلوب عندما تكون القيم :values موجودة.',
    'required_without'     => 'حقل :attribute مطلوب عندما تكون القيم :values غير موجودة.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا تكون أي من القيم :values موجودة.',
    'same'                 => 'يجب أن يتطابق :attribute مع :other.',

    'size'                 => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size.',
        'file'    => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute :size حروف.',
        'array'   => 'يجب أن يحتوي :attribute على :size عناصر.',
    ],

    'starts_with'          => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values.',
    'string'               => 'يجب أن يكون :attribute نصًا.',
    'timezone'             => 'يجب أن يكون :attribute منطقة زمنية صحيحة.',
    'unique'               => ':attribute مُستخدم من قبل.',
    'uploaded'             => 'فشل في تحميل :attribute.',
    'url'                  => 'صيغة :attribute غير صالحة.',
    'uuid'                 => 'يجب أن يكون :attribute بصيغة UUID صحيحة.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | هنا تقدر تترجم أسماء الحقول نفسها بدل ما تظهر بالانجليزية
    |
    */

    'attributes' => [
        'name'                  => 'الاسم',
        'username'              => 'اسم المستخدم',
        'email'                 => 'البريد الإلكتروني',
        'first_name'            => 'الاسم الأول',
        'last_name'             => 'اسم العائلة',
        'password'              => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'city'                  => 'المدينة',
        'country'               => 'الدولة',
        'address'               => 'العنوان',
        'phone'                 => 'رقم الهاتف',
        'mobile'                => 'الجوال',
        'age'                   => 'العمر',
        'sex'                   => 'الجنس',
        'gender'                => 'النوع',
        'day'                   => 'اليوم',
        'month'                 => 'الشهر',
        'year'                  => 'السنة',
        'hour'                  => 'الساعة',
        'minute'                => 'الدقيقة',
        'second'                => 'الثانية',
        'title'                 => 'العنوان',
        'content'               => 'المحتوى',
        'description'           => 'الوصف',
        'excerpt'               => 'المقتطف',
        'date'                  => 'التاريخ',
        'time'                  => 'الوقت',
        'available'             => 'متاح',
        'size'                  => 'الحجم',
        'file'                  => 'الملف',
        'image'                 => 'الصورة',
        'photo'                 => 'الصورة',
        'cat_id'                => 'القسم',
        'price'                 => 'السعر',
        'quantity'              => 'الكمية',
        'stock'                 => 'المخزون',
    ],


];
