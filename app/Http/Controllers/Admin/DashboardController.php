<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FormService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(FormService $formService): View
    {
        return view('admin.dashboard', [
            'metrics' => $formService->getAdminDashboardMetrics(),
        ]);
    }
}
