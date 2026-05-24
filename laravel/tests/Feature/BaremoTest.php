<?php

namespace Tests\Feature;

use Tests\TestCase;

class BaremoTest extends TestCase
{
    // ─── Validation ──────────────────────────────────────────────

    public function test_calculate_requires_edad(): void
    {
        $this->postJson(route('tools.baremo.calculate'), [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['edad']);
    }

    public function test_calculate_rejects_negative_edad(): void
    {
        $this->postJson(route('tools.baremo.calculate'), ['edad' => -1])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['edad']);
    }

    public function test_calculate_rejects_edad_over_120(): void
    {
        $this->postJson(route('tools.baremo.calculate'), ['edad' => 121])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['edad']);
    }

    // ─── Successful calculation ───────────────────────────────────

    public function test_calculate_returns_json_with_only_edad(): void
    {
        $this->postJson(route('tools.baremo.calculate'), ['edad' => 35])
             ->assertStatus(200)
             ->assertJson([]);  // any JSON object
    }

    public function test_calculate_returns_json_with_hospital_days(): void
    {
        $this->postJson(route('tools.baremo.calculate'), [
            'edad'          => 40,
            'dias_muy_grave' => 5,
            'dias_grave'     => 10,
            'dias_moderado'  => 15,
            'dias_basico'    => 20,
        ])->assertStatus(200)
          ->assertJsonStructure(['total_indemnizacion']);
    }

    public function test_calculate_with_sequelae_returns_json(): void
    {
        $this->postJson(route('tools.baremo.calculate'), [
            'edad'             => 45,
            'puntos_secuelas'  => 25,
            'puntos_esteticos' => 5,
        ])->assertStatus(200)
          ->assertJsonStructure(['total_indemnizacion']);
    }

    public function test_calculate_with_all_fields_returns_total(): void
    {
        $this->postJson(route('tools.baremo.calculate'), [
            'edad'             => 35,
            'dias_muy_grave'   => 3,
            'dias_grave'       => 7,
            'dias_moderado'    => 14,
            'dias_basico'      => 30,
            'puntos_secuelas'  => 20,
            'puntos_esteticos' => 8,
            'ingresos_anuales' => 30000,
            'gastos_medicos'   => 1500,
            'gastos_otros'     => 300,
        ])->assertStatus(200)
          ->assertJsonStructure(['total_indemnizacion']);
    }

    // ─── Page ────────────────────────────────────────────────────

    public function test_baremo_page_accessible_without_auth(): void
    {
        $this->get(route('tools.baremo.show'))->assertStatus(200);
    }
}
