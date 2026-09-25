<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Role;
use App\Models\SalaryHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeSalaryHistoryFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_create_employee_and_manage_salary_history(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->value('id'),
        ]);

        $this->actingAs($admin)
            ->post('/employees', [
                'nip' => '198501012010011001',
                'full_name' => 'Budi Pengujian',
                'birth_place' => 'Bandung',
                'birth_date' => '1985-01-01',
                'gender_id' => 1,
                'religion_id' => 1,
                'marital_status_id' => 1,
                'employment_status_id' => 1,
                'work_unit_id' => 1,
            ])
            ->assertRedirect();

        $employee = Employee::where('nip', '198501012010011001')->firstOrFail();
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'full_name' => 'Budi Pengujian',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->post("/employees/{$employee->id}/salary-history", [
                'new_salary' => 5000000,
                'effective_date' => '2025-01-01',
                'reason' => 'Gaji awal',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->post("/employees/{$employee->id}/salary-history", [
                'new_salary' => 5500000,
                'effective_date' => '2026-01-01',
                'reason' => 'Kenaikan berkala',
            ])
            ->assertRedirect();

        $employee->refresh();
        $this->assertSame(5500000, $employee->salary);
        $this->assertSame('2026-01-01', $employee->salary_tmt->toDateString());
        $this->assertDatabaseHas('salary_histories', [
            'employee_id' => $employee->id,
            'old_salary' => 5000000,
            'new_salary' => 5500000,
            'increase_amount' => 500000,
            'increase_percentage' => 10,
        ]);

        $latestHistory = SalaryHistory::where('employee_id', $employee->id)
            ->where('new_salary', 5500000)
            ->firstOrFail();

        $this->actingAs($admin)
            ->delete("/employees/{$employee->id}/salary-history/{$latestHistory->id}")
            ->assertRedirect();

        $employee->refresh();
        $this->assertSame(5000000, $employee->salary);
        $this->assertSame('2025-01-01', $employee->salary_tmt->toDateString());
        $this->assertDatabaseMissing('salary_histories', ['id' => $latestHistory->id]);
    }

    public function test_admin_can_view_salary_history_index(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->value('id'),
        ]);

        $response = $this->actingAs($admin)->get('/salary-history');
        $response->assertOk();
    }
}
