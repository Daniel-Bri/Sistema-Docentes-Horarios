<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Categoria;
use App\Models\Carrera;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\GestionAcademica;
use App\Models\GrupoMateria;
use App\Models\GrupoMateriaHorario;
use App\Models\Horario;
use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MateriaController extends Controller
{
    /**
     * Determinar la vista según el rol
     */
    private function getViewPath($viewName)
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return "admin.materias.{$viewName}";
        } elseif ($user->hasRole('coordinador')) {
            return "coordinador.materias.{$viewName}";
        } else {
            return "materias.{$viewName}";
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin') || $user->hasRole('coordinador')) {
            // SOLO relaciones que existen - ELIMINAR 'docentes'
            $materias = Materia::with(['categoria', 'carrera', 'grupoMaterias.grupo'])
                              ->orderBy('sigla')
                              ->paginate(10); //
        } else {
            $materias = $this->getMateriasForDocente($user);
        }
        
        $view = $this->getViewPath('index');
        return view($view, compact('materias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $carreras = Carrera::all();
        
        $view = $this->getViewPath('create');
        return view($view, compact('categorias', 'carreras'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'sigla' => 'required|unique:materia,sigla|max:10',
        'nombre' => 'required|max:255',
        'semestre' => 'required|integer',
        'id_categoria' => 'required|exists:categoria,id',
        'id_carrera' => 'required|exists:carrera,id'
    ]);

    try {
        Materia::create($request->all());
        
        return redirect()->route('admin.materias.index') // ✅ CORREGIDO
                       ->with('success', 'Materia creada exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()
                       ->with('error', 'Error al crear la materia: ' . $e->getMessage())
                       ->withInput();
    }
}

    /**
     * Display the specified resource.
     */
public function show($sigla)
{
    try {
        // Cargar relaciones de forma segura
        $materia = Materia::with([
            'categoria', 
            'carrera', 
            'grupoMaterias.grupo',
            'grupoMaterias.gestion',
            'grupoMaterias.horarios.horario',
            'grupoMaterias.horarios.aula',
            'grupoMaterias.horarios.docente' // Ahora esta relación existe
        ])->findOrFail($sigla);

        $view = $this->getViewPath('show');
        return view($view, compact('materia'));
        
    } catch (\Illuminate\Database\Eloquent\RelationNotFoundException $e) {
        // Si falla alguna relación, cargar solo las básicas
        $materia = Materia::with([
            'categoria', 
            'carrera', 
            'grupoMaterias.grupo',
            'grupoMaterias.gestion'
        ])->findOrFail($sigla);

        // Cargar horarios por separado para debug
        $materia->load(['grupoMaterias.horarios' => function($query) {
            $query->with(['horario', 'aula', 'docente']);
        }]);

        $view = $this->getViewPath('show');
        return view($view, compact('materia'));
    }
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sigla)
    {
        $materia = Materia::findOrFail($sigla);
        $categorias = Categoria::all();
        $carreras = Carrera::all();

        $view = $this->getViewPath('edit');
        return view($view, compact('materia', 'categorias', 'carreras'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $sigla)
{
    $materia = Materia::findOrFail($sigla);

    $request->validate([
        'sigla' => 'required|max:10|unique:materia,sigla,' . $materia->sigla . ',sigla',
        'nombre' => 'required|max:255',
        'semestre' => 'required|integer',
        'id_categoria' => 'required|exists:categoria,id',
        'id_carrera' => 'required|exists:carrera,id'
    ]);

    try {
        $materia->update($request->all());
        
        return redirect()->route('admin.materias.index') // ✅ CORREGIDO
                       ->with('success', 'Materia actualizada exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()
                       ->with('error', 'Error al actualizar la materia: ' . $e->getMessage())
                       ->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy($sigla)
{
    $materia = Materia::findOrFail($sigla);

    try {
        // Verificar si tiene grupos asignados
        if ($materia->grupoMaterias()->exists()) {
            return redirect()->back()
                           ->with('error', 'No se puede eliminar la materia porque tiene grupos asignados.');
        }
        
        $materia->delete();
        
        return redirect()->route('admin.materias.index') // ✅ CORREGIDO
                       ->with('success', 'Materia eliminada exitosamente.');
    } catch (\Exception $e) {
        return redirect()->back()
                       ->with('error', 'Error al eliminar la materia: ' . $e->getMessage());
    }
}

    /**
     * Asignar materia a grupo
     */
public function asignarGrupo($sigla)
{
    $materia = Materia::findOrFail($sigla);
    $grupos = Grupo::all();
    $gestiones = GestionAcademica::where('estado', 'activa')->get();
    $docentes = Docente::all();
    $horarios = Horario::all();
    $aulas = Aula::all();

    $view = $this->getViewPath('asignar-grupo');
    return view($view, compact('materia', 'grupos', 'gestiones', 'docentes', 'horarios', 'aulas'));
}

    /**
     * Procesar asignación de materia a grupo
     */
    public function storeAsignarGrupo(Request $request, $sigla)
    {
        $request->validate([
            'id_grupo' => 'required|exists:grupo,id',
            'id_gestion' => 'required|exists:gestion_academica,id',
            'horarios' => 'required|array|min:1',
            'horarios.*.id_horario' => 'required|exists:horario,id',
            'horarios.*.codigo_docente' => 'required|exists:docente,codigo',
            'horarios.*.id_aula' => 'required|exists:aula,id'
        ]);

        try {
            DB::beginTransaction();

            // Crear GrupoMateria
            $grupoMateria = GrupoMateria::create([
                'id_grupo' => $request->id_grupo,
                'sigla_materia' => $sigla,
                'id_gestion' => $request->id_gestion
            ]);

            // Crear horarios
            foreach ($request->horarios as $horarioData) {
                GrupoMateriaHorario::create([
                    'id_grupo_materia' => $grupoMateria->id,
                    'id_horario' => $horarioData['id_horario'],
                    'codigo_docente' => $horarioData['codigo_docente'],
                    'id_aula' => $horarioData['id_aula']
                ]);
            }

            DB::commit();

            return redirect()->route('materias.show', $sigla)
                           ->with('success', 'Materia asignada al grupo exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al asignar materia al grupo: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Obtener materias para docente
     */
    private function getMateriasForDocente($user)
    {
        // Esta función necesita revisión ya que no hay relación directa docente-materia
        return collect();
    }

    /**
     * Mostrar horarios de una materia
     */
    public function horarios($sigla)
    {
        $materia = Materia::with([
            'grupoMaterias.horarios.horario',
            'grupoMaterias.horarios.aula',
            'grupoMaterias.horarios.docente',
            'grupoMaterias.grupo',
            'grupoMaterias.gestion' // ← Cambiado aquí también
        ])->findOrFail($sigla);

        $view = $this->getViewPath('horarios');
        return view($view, compact('materia'));
    }
    /**
     * API: Obtener horarios disponibles
     */
    public function getHorarios()
    {
        $horarios = Horario::all();
        return response()->json($horarios);
    }

    /**
     * API: Obtener aulas disponibles
     */
    public function getAulas()
    {
        $aulas = Aula::all();
        return response()->json($aulas);
    }
    
}