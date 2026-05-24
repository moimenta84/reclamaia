<?php

namespace App\Services;

/**
 * Baremo de tráfico — Sistema de valoración de daños corporales.
 * Valores actualizados conforme a Resolución DGS (actualización 2024).
 * Base: Real Decreto 1148/2015 (modificado por Ley 35/2015).
 *
 * IMPORTANTE: Estos valores son orientativos. El baremo oficial se actualiza
 * anualmente mediante Resolución de la DGS. Siempre contrastar con la
 * resolución vigente antes de presentar una reclamación formal.
 */
class BaremoService
{
    // ── Tabla 1: Perjuicio personal básico por días (€/día, 2024) ─────────────
    private const INJURY_DAY_RATES = [
        'muy_grave'  => 115.80,  // Hospitalización UCI / estado muy grave
        'grave'      => 78.32,   // Hospitalización planta
        'moderado'   => 56.08,   // Baja laboral sin hospitalización
        'basico'     => 33.28,   // Curación sin impedimento laboral
    ];

    // ── Tabla 2: Perjuicio estético (€/punto) ─────────────────────────────────
    private const AESTHETIC_RATE = 1_320.00;  // por punto de perjuicio estético

    // ── Tabla 3: Secuelas — valor por punto según edad (€/punto) ──────────────
    // [edad_min, edad_max, €/punto]
    private const SEQUELAE_BY_AGE = [
        [0,  5,  3_215.00],
        [6,  10, 3_018.00],
        [11, 15, 2_789.00],
        [16, 20, 2_540.00],
        [21, 25, 2_345.00],
        [26, 30, 2_187.00],
        [31, 35, 2_021.00],
        [36, 40, 1_856.00],
        [41, 45, 1_697.00],
        [46, 50, 1_532.00],
        [51, 55, 1_371.00],
        [56, 60, 1_208.00],
        [61, 65, 1_048.00],
        [66, 70,   895.00],
        [71, 75,   748.00],
        [76, 80,   610.00],
        [81, 99,   485.00],
    ];

    // ── Tabla 4: Factores de corrección perjuicio patrimonial ─────────────────
    // Incremento por ingresos perdidos (% sobre base)
    private const INCOME_CORRECTION = [
        ['hasta' => 21_000,  'factor' => 0.00],
        ['hasta' => 35_000,  'factor' => 0.10],
        ['hasta' => 50_000,  'factor' => 0.25],
        ['hasta' => 75_000,  'factor' => 0.50],
        ['hasta' => 100_000, 'factor' => 0.75],
        ['hasta' => PHP_INT_MAX, 'factor' => 1.00],
    ];

    /**
     * Calculate full indemnification based on injury data.
     *
     * @param  array  $data {
     *   dias_muy_grave:   int,
     *   dias_grave:       int,
     *   dias_moderado:    int,
     *   dias_basico:      int,
     *   edad:             int,
     *   puntos_secuelas:  float,
     *   puntos_esteticos: float,
     *   ingresos_anuales: float,
     *   gastos_medicos:   float,
     *   gastos_otros:     float,
     * }
     */
    public function calculate(array $data): array
    {
        $days = $this->calculateInjuryDays($data);
        $seq  = $this->calculateSequelae($data);
        $aes  = $this->calculateAesthetic($data);
        $pat  = $this->calculatePatrimonial($data);

        $base  = $days['total'] + $seq['total'] + $aes['total'];
        $total = $base + $pat['total'];

        return [
            'dias_perjuicio'     => $days,
            'secuelas'           => $seq,
            'perjuicio_estetico' => $aes,
            'perjuicio_patrimonial' => $pat,
            'subtotal_corporal'  => round($base, 2),
            'total_indemnizacion' => round($total, 2),
            'nota_legal'         => 'Valores calculados según Resolución DGS 2024 (Ley 35/2015). Carácter orientativo.',
            'actualizar_url'     => 'https://www.dgsfp.mineco.gob.es/sector/seguros/SegurosAccidentesTrafico.asp',
        ];
    }

    private function calculateInjuryDays(array $d): array
    {
        $rates   = self::INJURY_DAY_RATES;
        $results = [];
        $total   = 0.0;

        foreach (['muy_grave', 'grave', 'moderado', 'basico'] as $type) {
            $days        = (int) ($d["dias_{$type}"] ?? 0);
            $amount      = $days * $rates[$type];
            $total      += $amount;
            $results[$type] = [
                'dias'   => $days,
                'tarifa' => $rates[$type],
                'total'  => round($amount, 2),
            ];
        }

        return array_merge($results, ['total' => round($total, 2)]);
    }

    private function calculateSequelae(array $d): array
    {
        $edad   = (int) ($d['edad'] ?? 40);
        $puntos = (float) ($d['puntos_secuelas'] ?? 0);
        $rate   = $this->getSequelaeRate($edad);
        $total  = $puntos * $rate;

        return [
            'puntos'      => $puntos,
            'edad'        => $edad,
            'tarifa_punto' => $rate,
            'total'       => round($total, 2),
        ];
    }

    private function calculateAesthetic(array $d): array
    {
        $puntos = (float) ($d['puntos_esteticos'] ?? 0);
        $total  = $puntos * self::AESTHETIC_RATE;

        return [
            'puntos' => $puntos,
            'tarifa' => self::AESTHETIC_RATE,
            'total'  => round($total, 2),
        ];
    }

    private function calculatePatrimonial(array $d): array
    {
        $ingresos = (float) ($d['ingresos_anuales'] ?? 0);
        $medicos  = (float) ($d['gastos_medicos'] ?? 0);
        $otros    = (float) ($d['gastos_otros'] ?? 0);

        $factor      = $this->getIncomeFactor($ingresos);
        $lucro       = $ingresos * $factor;
        $total       = $lucro + $medicos + $otros;

        return [
            'ingresos_anuales'   => $ingresos,
            'factor_correccion'  => $factor,
            'lucro_cesante'      => round($lucro, 2),
            'gastos_medicos'     => $medicos,
            'gastos_otros'       => $otros,
            'total'              => round($total, 2),
        ];
    }

    private function getSequelaeRate(int $edad): float
    {
        foreach (self::SEQUELAE_BY_AGE as [$min, $max, $rate]) {
            if ($edad >= $min && $edad <= $max) {
                return $rate;
            }
        }
        return 485.00;
    }

    private function getIncomeFactor(float $ingresos): float
    {
        foreach (self::INCOME_CORRECTION as $bracket) {
            if ($ingresos <= $bracket['hasta']) {
                return $bracket['factor'];
            }
        }
        return 1.0;
    }
}
