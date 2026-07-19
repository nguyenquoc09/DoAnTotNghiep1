<?php

return [
    'required' => 'Trường :attribute là bắt buộc.',
    'email' => ':attribute phải là địa chỉ email hợp lệ.',
    'unique' => ':attribute đã được sử dụng.',
    'exists' => ':attribute không tồn tại trong hệ thống.',
    'date' => ':attribute không phải ngày hợp lệ.',
    'before_or_equal' => ':attribute phải trước hoặc bằng :date.',
    'after_or_equal' => ':attribute phải sau hoặc bằng :date.',
    'numeric' => ':attribute phải là số.',
    'integer' => ':attribute phải là số nguyên.',
    'min' => ['numeric' => ':attribute tối thiểu là :min.', 'string' => ':attribute phải có ít nhất :min ký tự.'],
    'max' => ['numeric' => ':attribute không được lớn hơn :max.', 'string' => ':attribute không được vượt quá :max ký tự.'],
    'confirmed' => 'Xác nhận :attribute chưa khớp.',
    'attributes' => ['name' => 'họ tên', 'email' => 'email', 'phone' => 'số điện thoại', 'password' => 'mật khẩu', 'appointment_time' => 'khung giờ', 'reason' => 'lý do khám', 'amount' => 'số tiền'],
];
