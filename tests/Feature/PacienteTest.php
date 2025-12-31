<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Servicio;
use App\Models\Cama;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PacienteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that bed assignment is preserved when updating a patient.
     */
    public function test_bed_assignment_is_saved_when_updating_patient(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'administrador',
        ]);

        // Create a servicio
        $servicio = Servicio::create([
            'nombre' => 'Medicina Interna',
        ]);

        // Create a cama
        $cama = Cama::create([
            'codigo' => 'A-101',
            'servicio_id' => $servicio->id,
        ]);

        // Create a patient
        $paciente = Paciente::create([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'cedula' => '1234567890',
            'estado' => 'hospitalizado',
            'edad' => 45,
            'condicion' => 'normal',
            'servicio_id' => $servicio->id,
            'cama_id' => null,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // Update the patient with a bed assignment
        $response = $this->actingAs($admin)->put(route('pacientes.update', $paciente), [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'cedula' => '1234567890',
            'estado' => 'hospitalizado',
            'edad' => 45,
            'condicion' => ['normal'],
            'servicio_id' => $servicio->id,
            'cama_id' => $cama->id,
        ]);

        $response->assertRedirect(route('pacientes.index'));
        $response->assertSessionHas('success', 'Paciente actualizado.');

        // Verify the bed assignment was saved
        $paciente->refresh();
        $this->assertEquals($cama->id, $paciente->cama_id);
    }

    /**
     * Test that bed assignment is nullified when patient is discharged (alta).
     */
    public function test_bed_assignment_is_cleared_when_patient_discharged(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'administrador',
        ]);

        // Create servicios
        $servicio = Servicio::create([
            'nombre' => 'Medicina Interna',
        ]);

        $servicioAlta = Servicio::create([
            'nombre' => 'ALTA',
        ]);

        // Create a cama
        $cama = Cama::create([
            'codigo' => 'A-101',
            'servicio_id' => $servicio->id,
        ]);

        // Create a patient with bed assignment
        $paciente = Paciente::create([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'cedula' => '1234567890',
            'estado' => 'hospitalizado',
            'edad' => 45,
            'condicion' => 'normal',
            'servicio_id' => $servicio->id,
            'cama_id' => $cama->id,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // Update the patient to alta status
        $response = $this->actingAs($admin)->put(route('pacientes.update', $paciente), [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'cedula' => '1234567890',
            'estado' => 'alta',
            'edad' => 45,
            'condicion' => ['normal'],
            'servicio_id' => $servicio->id,
            'cama_id' => $cama->id, // Even if sent, should be nullified
        ]);

        $response->assertRedirect(route('pacientes.index'));

        // Verify the bed assignment was cleared
        $paciente->refresh();
        $this->assertNull($paciente->cama_id);
        $this->assertEquals($servicioAlta->id, $paciente->servicio_id);
    }

    /**
     * Test that bed assignment is prevented for Diálisis service.
     */
    public function test_bed_assignment_is_cleared_for_dialisis_service(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'administrador',
        ]);

        // Create servicios
        $servicio = Servicio::create([
            'nombre' => 'Diálisis',
        ]);

        // Create a cama (shouldn't be used)
        $cama = Cama::create([
            'codigo' => 'D-101',
            'servicio_id' => $servicio->id,
        ]);

        // Create a patient
        $paciente = Paciente::create([
            'nombre' => 'Maria',
            'apellido' => 'Lopez',
            'cedula' => '0987654321',
            'estado' => 'hospitalizado',
            'edad' => 60,
            'condicion' => 'diabetico',
            'servicio_id' => $servicio->id,
            'cama_id' => null,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        // Try to update with bed assignment
        $response = $this->actingAs($admin)->put(route('pacientes.update', $paciente), [
            'nombre' => 'Maria',
            'apellido' => 'Lopez',
            'cedula' => '0987654321',
            'estado' => 'hospitalizado',
            'edad' => 60,
            'condicion' => ['diabetico'],
            'servicio_id' => $servicio->id,
            'cama_id' => $cama->id,
        ]);

        $response->assertRedirect(route('pacientes.index'));

        // Verify the bed assignment was not saved
        $paciente->refresh();
        $this->assertNull($paciente->cama_id);
    }
}
