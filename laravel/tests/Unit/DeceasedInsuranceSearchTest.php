<?php

namespace Tests\Unit;

use App\Models\DeceasedInsuranceSearch;
use Tests\TestCase;

/**
 * Unit tests del modelo DeceasedInsuranceSearch.
 * Verifica statusLabel(), statusColor() y nextStep().
 */
class DeceasedInsuranceSearchTest extends TestCase
{
    private function makeSearch(string $status): DeceasedInsuranceSearch
    {
        $search = new DeceasedInsuranceSearch();
        $search->status = $status;
        return $search;
    }

    // ─── statusLabel() ─────────────────────────────────────────────────

    /** @dataProvider statusLabelProvider */
    public function test_status_label_returns_correct_string(string $status, string $expected): void
    {
        $this->assertSame($expected, $this->makeSearch($status)->statusLabel());
    }

    public static function statusLabelProvider(): array
    {
        return [
            ['pendiente_documentacion', 'Pendiente documentación'],
            ['tramite_iniciado',        'Trámite iniciado'],
            ['certificado_recibido',    'Certificado recibido'],
            ['seguro_encontrado',       'Seguro encontrado'],
            ['seguro_no_encontrado',    'Sin seguro registrado'],
            ['reclamacion_enviada',     'Reclamación enviada'],
            ['cobrado',                 'Cobrado'],
        ];
    }

    // ─── statusColor() ─────────────────────────────────────────────────

    /** @dataProvider statusColorProvider */
    public function test_status_color_returns_bootstrap_class(string $status, string $expectedColor): void
    {
        $color = $this->makeSearch($status)->statusColor();
        $this->assertSame($expectedColor, $color);
        // Debe ser un color Bootstrap válido
        $this->assertContains($color, ['secondary', 'primary', 'info', 'warning', 'danger', 'success']);
    }

    public static function statusColorProvider(): array
    {
        return [
            ['pendiente_documentacion', 'secondary'],
            ['tramite_iniciado',        'primary'],
            ['certificado_recibido',    'info'],
            ['seguro_encontrado',       'warning'],
            ['seguro_no_encontrado',    'danger'],
            ['reclamacion_enviada',     'warning'],
            ['cobrado',                 'success'],
        ];
    }

    // ─── nextStep() ────────────────────────────────────────────────────

    public function test_next_step_is_not_empty_for_all_statuses(): void
    {
        $statuses = [
            'pendiente_documentacion',
            'tramite_iniciado',
            'certificado_recibido',
            'seguro_encontrado',
            'seguro_no_encontrado',
            'reclamacion_enviada',
            'cobrado',
        ];

        foreach ($statuses as $status) {
            $nextStep = $this->makeSearch($status)->nextStep();
            $this->assertNotEmpty($nextStep, "nextStep() vacío para status: {$status}");
        }
    }

    public function test_next_step_mentions_art_20_lcs_when_claim_sent(): void
    {
        $nextStep = $this->makeSearch('reclamacion_enviada')->nextStep();
        $this->assertStringContainsString('art. 20 LCS', $nextStep);
    }

    public function test_next_step_mentions_ministerio_when_pending(): void
    {
        $nextStep = $this->makeSearch('pendiente_documentacion')->nextStep();
        $this->assertStringContainsString('Ministerio', $nextStep);
    }

    // ─── Status transitions ────────────────────────────────────────────

    public function test_unknown_status_returns_fallback_label(): void
    {
        $label = $this->makeSearch('estado_desconocido')->statusLabel();
        $this->assertNotEmpty($label);
    }

    public function test_unknown_status_returns_secondary_color(): void
    {
        $color = $this->makeSearch('estado_desconocido')->statusColor();
        $this->assertSame('secondary', $color);
    }
}
