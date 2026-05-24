<?php

namespace App\Http\Controllers;

use App\Services\BaremoService;
use Illuminate\Http\Request;

class BaremoController extends Controller
{
    public function __construct(private BaremoService $baremo) {}

    public function show()
    {
        return view('tools.baremo');
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'edad'             => 'required|integer|min:0|max:120',
            'dias_muy_grave'   => 'nullable|integer|min:0',
            'dias_grave'       => 'nullable|integer|min:0',
            'dias_moderado'    => 'nullable|integer|min:0',
            'dias_basico'      => 'nullable|integer|min:0',
            'puntos_secuelas'  => 'nullable|numeric|min:0|max:100',
            'puntos_esteticos' => 'nullable|numeric|min:0|max:50',
            'ingresos_anuales' => 'nullable|numeric|min:0',
            'gastos_medicos'   => 'nullable|numeric|min:0',
            'gastos_otros'     => 'nullable|numeric|min:0',
        ]);

        $result = $this->baremo->calculate($data);

        return response()->json($result);
    }
}
