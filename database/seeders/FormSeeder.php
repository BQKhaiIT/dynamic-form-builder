<?php

namespace Database\Seeders;

use App\Enums\FormFieldType;
use App\Enums\FormStatus;
use App\Models\FieldOption;
use App\Models\Form;
use App\Models\FormField;
use App\Models\User;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $this->createEmployeeSurvey($admin->id);
        $this->createPerformanceReview($admin->id);
        $this->createLeaveRequest($admin->id);
        $this->createITSupportRequest($admin->id);
    }

    private function createEmployeeSurvey(int $adminId): void
    {
        $form = Form::create([
            'created_by' => $adminId,
            'title' => 'Khảo sát mức độ hài lòng của nhân viên',
            'description' => 'Thu thập ý kiến nhằm cải thiện môi trường làm việc.',
            'status' => FormStatus::Active,
        ]);

        $this->text($form, 'Họ và tên', 'Nhập họ và tên', true, 1);

        $department = $this->select(
            $form,
            'Phòng ban',
            true,
            2,
            [
                'Công nghệ thông tin',
                'Nhân sự',
                'Kế toán',
                'Marketing',
                'Kinh doanh',
                'Chăm sóc khách hàng',
            ]
        );

        $this->number($form, 'Số năm làm việc', true, 3);

        $this->date($form, 'Ngày vào công ty', true, 4);

        $this->color($form, 'Màu sắc yêu thích', false, 5);

        $this->text($form, 'Góp ý', 'Ý kiến đóng góp...', false, 6);
    }

    private function createPerformanceReview(int $adminId): void
    {
        $form = Form::create([
            'created_by' => $adminId,
            'title' => 'Đánh giá hiệu suất hàng tháng',
            'description' => 'Đánh giá kết quả làm việc của nhân viên.',
            'status' => FormStatus::Active,
        ]);

        $this->text($form, 'Mã nhân viên', 'VD: EMP001', true, 1);

        $this->text($form, 'Tên dự án', 'Tên dự án đang tham gia', true, 2);

        $this->number($form, 'Số giờ làm việc', true, 3);

        $this->number($form, 'Điểm đánh giá', true, 4);

        $this->text($form, 'Nhận xét', 'Nhập nhận xét...', false, 5);
    }

    private function createLeaveRequest(int $adminId): void
    {
        $form = Form::create([
            'created_by' => $adminId,
            'title' => 'Đơn xin nghỉ phép',
            'description' => 'Đăng ký nghỉ phép trực tuyến.',
            'status' => FormStatus::Active,
        ]);

        $this->select(
            $form,
            'Loại nghỉ phép',
            true,
            1,
            [
                'Nghỉ phép năm',
                'Nghỉ ốm',
                'Nghỉ không lương',
            ]
        );

        $this->date($form, 'Ngày bắt đầu', true, 2);

        $this->date($form, 'Ngày kết thúc', true, 3);

        $this->text($form, 'Lý do', 'Nhập lý do...', true, 4);
    }

    private function createITSupportRequest(int $adminId): void
    {
        $form = Form::create([
            'created_by' => $adminId,
            'title' => 'Yêu cầu hỗ trợ CNTT',
            'description' => 'Gửi yêu cầu hỗ trợ tới bộ phận IT.',
            'status' => FormStatus::Active,
        ]);
        $this->text($form, 'Tiêu đề', 'Mô tả ngắn gọn sự cố', true, 1);

        $this->select(
            $form,
            'Danh mục',
            true,
            2,
            [
                'Máy tính',
                'Máy in',
                'Email',
                'Mạng',
                'Phần mềm',
            ]
        );

        $this->select(
            $form,
            'Mức độ ưu tiên',
            true,
            3,
            [
                'Thấp',
                'Trung bình',
                'Cao',
                'Khẩn cấp',
            ]
        );

        $this->text($form, 'Mô tả chi tiết', 'Mô tả sự cố...', true, 4);
    }

    /* ---------------- Helper Methods ---------------- */

    private function text(Form $form, string $label, ?string $placeholder, bool $required, int $order): FormField
    {
        return FormField::create([
            'form_id' => $form->id,
            'label' => $label,
            'type' => FormFieldType::Text,
            'placeholder' => $placeholder,
            'required' => $required,
            'sort_order' => $order,
        ]);
    }

    private function number(Form $form, string $label, bool $required, int $order): FormField
    {
        return FormField::create([
            'form_id' => $form->id,
            'label' => $label,
            'type' => FormFieldType::Number,
            'placeholder' => null,
            'required' => $required,
            'sort_order' => $order,
        ]);
    }

    private function date(Form $form, string $label, bool $required, int $order): FormField
    {
        return FormField::create([
            'form_id' => $form->id,
            'label' => $label,
            'type' => FormFieldType::Date,
            'placeholder' => null,
            'required' => $required,
            'sort_order' => $order,
        ]);
    }

    private function color(Form $form, string $label, bool $required, int $order): FormField
    {
        return FormField::create([
            'form_id' => $form->id,
            'label' => $label,
            'type' => FormFieldType::Color,
            'placeholder' => null,
            'required' => $required,
            'sort_order' => $order,
        ]);
    }

    private function select(
        Form $form,
        string $label,
        bool $required,
        int $order,
        array $options
    ): FormField {
        $field = FormField::create([
            'form_id' => $form->id,
            'label' => $label,
            'type' => FormFieldType::Select,
            'placeholder' => null,
            'required' => $required,
            'sort_order' => $order,
        ]);

        foreach ($options as $index => $option) {
            FieldOption::create([
                'field_id' => $field->id,
                'label' => $option,
                'value' => $option,
                'sort_order' => $index + 1,
            ]);
        }

        return $field;
    }
}
