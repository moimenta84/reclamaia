<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    public static array $articles = [
        [
            'slug'        => 'que-hacer-si-seguro-rechaza-siniestro',
            'title'       => 'Qué hacer si tu seguro rechaza el siniestro',
            'description' => 'La aseguradora ha denegado tu reclamación. Estos son los pasos legales exactos que tienes que seguir según la Ley de Contrato de Seguro española.',
            'category'    => 'Guía práctica',
            'date'        => '2024-11-12',
            'read_min'    => 8,
        ],
        [
            'slug'        => 'como-reclamar-indemnizacion-inundacion',
            'title'       => 'Cómo reclamar una indemnización por inundación',
            'description' => 'DANA, lluvias torrenciales, desbordamiento de ríos: paso a paso para reclamar a tu seguro de hogar y al Consorcio de Compensación de Seguros.',
            'category'    => 'Desastres naturales',
            'date'        => '2024-10-30',
            'read_min'    => 10,
        ],
        [
            'slug'        => 'cuanto-tarda-reclamacion-aseguradora',
            'title'       => 'Cuánto tarda una reclamación a una aseguradora',
            'description' => 'El art. 18 LCS fija plazos concretos. Qué pasa si la aseguradora no responde, cuándo puedes acudir a la DGSFP y cómo presionarla legalmente.',
            'category'    => 'Normativa',
            'date'        => '2024-10-15',
            'read_min'    => 6,
        ],
        [
            'slug'        => 'que-hacer-si-seguro-no-paga',
            'title'       => 'Qué hacer si el seguro no paga',
            'description' => 'Cinco acciones concretas cuando la aseguradora ignora tu reclamación: desde el Defensor del Asegurado hasta la DGSFP y los juzgados.',
            'category'    => 'Guía práctica',
            'date'        => '2024-09-28',
            'read_min'    => 7,
        ],
        [
            'slug'        => 'plazos-prescripcion-seguros-espana',
            'title'       => 'Plazos de prescripción de seguros en España',
            'description' => 'Cada ramo tiene su propio plazo: vida (5 años), daños (2 años), accidentes (1 año). Cómo calcularlos y cómo interrumpir la prescripción.',
            'category'    => 'Normativa',
            'date'        => '2024-09-10',
            'read_min'    => 5,
        ],
        [
            'slug'        => 'defensor-asegurado-vs-dgsfp',
            'title'       => 'Defensor del Asegurado vs DGSFP: cuándo usar cada uno',
            'description' => 'Dos vías extrajudiciales muy distintas. Cuándo sirve el Defensor del Asegurado, cuándo acudir a la DGSFP y qué esperar de cada proceso.',
            'category'    => 'Normativa',
            'date'        => '2024-08-22',
            'read_min'    => 7,
        ],
        [
            'slug'        => 'reclamar-seguro-hogar-danos-agua',
            'title'       => 'Cómo reclamar al seguro de hogar por daños de agua',
            'description' => 'Goteras, tuberías reventadas, condensación, vecino de arriba: qué cubre cada póliza y cómo documentar los daños para cobrar la indemnización.',
            'category'    => 'Hogar',
            'date'        => '2024-08-05',
            'read_min'    => 9,
        ],
        [
            'slug'        => 'seguros-vida-no-reclamados-espana',
            'title'       => 'Seguros de vida no reclamados en España: cómo encontrarlos',
            'description' => 'Miles de millones en seguros de vida no reclamados. Cómo consultar el Registro de Contratos de Seguros de Cobertura de Fallecimiento y qué hacer si encuentras uno.',
            'category'    => 'Vida y fallecidos',
            'date'        => '2024-07-18',
            'read_min'    => 8,
        ],
    ];

    public static array $guias = [
        [
            'slug'        => 'guia-completa-reclamar-seguro-espana',
            'title'       => 'Guía completa para reclamar a tu seguro en España',
            'description' => 'Manual paso a paso: desde la comunicación del siniestro hasta el cobro de la indemnización. Incluye modelos de carta, plazos y normativa actualizada 2024.',
            'icon'        => '📘',
        ],
        [
            'slug'        => 'guia-ley-contrato-seguro',
            'title'       => 'La Ley de Contrato de Seguro explicada sin tecnicismos',
            'description' => 'Los artículos clave de la LCS que todo asegurado debe conocer: art. 18 (plazos), art. 38 (perito), art. 104 (silencio), art. 20 (intereses moratorios).',
            'icon'        => '⚖️',
        ],
        [
            'slug'        => 'guia-baremo-trafico-2024',
            'title'       => 'Baremo de tráfico 2024: cómo calcular tu indemnización',
            'description' => 'La Ley 35/2015 y la Resolución DGS 2024 actualizada. Tablas, factores de corrección y cómo maximizar la indemnización por accidente de tráfico.',
            'icon'        => '🚗',
        ],
        [
            'slug'        => 'guia-dgsfp-reclamaciones',
            'title'       => 'Cómo reclamar ante la DGSFP paso a paso',
            'description' => 'La Dirección General de Seguros tiene poder sancionador sobre las aseguradoras. Modelo de reclamación, plazos y qué esperar del proceso.',
            'icon'        => '🏛️',
        ],
    ];

    public function index(): View
    {
        return view('blog.index', ['articles' => self::$articles]);
    }

    public function show(string $slug): View|Response
    {
        $article = collect(self::$articles)->firstWhere('slug', $slug);
        if (! $article) {
            abort(404);
        }
        $related = collect(self::$articles)
            ->where('slug', '!=', $slug)
            ->take(3)
            ->values()
            ->all();
        return view('blog.show', compact('article', 'related'));
    }

    public function guiasIndex(): View
    {
        return view('guias.index', ['guias' => self::$guias]);
    }

    public function guia(string $slug): View|Response
    {
        $guia = collect(self::$guias)->firstWhere('slug', $slug);
        if (! $guia) {
            abort(404);
        }
        return view('guias.show', compact('guia'));
    }
}
