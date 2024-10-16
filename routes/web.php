<?php

use App\Models\Orden;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// FIXME: Esto deberia estar en un controller o un action / service
Route::get('/ordens/{orden}/pdf/detalle', function (Orden $orden) {
    $categorias = $orden->examens()->with('categoria')->get()->pluck('categoria');
    // Si deseas obtener una colección única de categorías, puedes hacerlo así
    $categoriasUnicas = $categorias->unique('id');

    // Si necesitas trabajar con un array de las categorías en lugar de una colección de Eloquent
    // $categoriasArray = $categoriasUnicas->toArray();
    // return $categoriasUnicas;
    $data = ['categorias' => $categoriasUnicas, 'orden' => $orden];
    $pdf = Pdf::loadView('pdf.orden-pdf', $data)
        ->setPaper('a5');
    return response($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="orden.pdf"',
    ]);
})->name('orden.pdf.detalle');

Route::get('/ordens/{orden}/pdf/resultados', function (Orden $orden) {
    $categorias = $orden->examens()->with('categoria')->get()->pluck('categoria');
    $examenesByCategoria = $orden->examens->where('parent_id', null)->groupBy('categoria.nombre');
    // dd($examenesByCategoria);
    // Si deseas obtener una colección única de categorías, puedes hacerlo así
    $categoriasUnicas = $categorias->unique('id');

    // Si necesitas trabajar con un array de las categorías en lugar de una colección de Eloquent
    // $categoriasArray = $categoriasUnicas->toArray();
    // return $categoriasUnicas;
    $data = ['categorias' => $examenesByCategoria, 'orden' => $orden->loadMissing(['examens', 'examens.children', 'examens.categoria'])];
    $pdf = Pdf::loadView('pdf.orden-resultados-pdf', $data)
        ->setPaper('a5');
    // return view('pdf.orden-resultados-pdf', $data);
    return response($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="orden.pdf"',
    ]);
})->name('orden.pdf.resultado');
