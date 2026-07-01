<?php

namespace Database\Seeders;

use App\Services\AdminUserService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminUserService = app(AdminUserService::class);

        // Administrator
        $adminUserService->createOrUpdateAdmin(
            name: 'Quản trị hệ thống',
            email: 'admin@example.com',
            password: 'password',
        );

        // Demo Employee
        $adminUserService->createOrUpdateEmployee(
            name: 'Nguyễn Văn An',
            email: 'employee@example.com',
            password: 'password',
        );

        $employees = [
            ['Trần Thị Mai', 'mai.tran@example.com'],
            ['Lê Minh Quân', 'quan.le@example.com'],
            ['Phạm Hoàng Long', 'long.pham@example.com'],
            ['Vũ Thu Hà', 'ha.vu@example.com'],
            ['Đặng Gia Huy', 'huy.dang@example.com'],
            ['Bùi Ngọc Anh', 'anh.bui@example.com'],
            ['Đỗ Thanh Tùng', 'tung.do@example.com'],
            ['Hoàng Minh Đức', 'duc.hoang@example.com'],
            ['Phan Hải Nam', 'nam.phan@example.com'],
            ['Ngô Khánh Linh', 'linh.ngo@example.com'],
            ['Lý Gia Bảo', 'bao.ly@example.com'],
            ['Đinh Thu Trang', 'trang.dinh@example.com'],
        ];

        foreach ($employees as [$name, $email]) {
            $adminUserService->createOrUpdateEmployee(
                name: $name,
                email: $email,
                password: 'password',
            );
        }
    }
}
