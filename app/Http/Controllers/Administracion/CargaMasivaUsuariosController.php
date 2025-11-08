<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Docente;
use App\Models\GestionAcademica;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CargaMasivaUsuariosController extends Controller
{
    public function index()
    {
        $gestiones = GestionAcademica::whereIn('estado', ['curso', 'activo'])->get();
        return view('cargaMasiva.index', compact('gestiones'));
    }

    public function preview(Request $request)
    {
        try {
            // Validar archivo
            $request->validate([
                'archivo_usuarios' => 'required|file|mimes:csv,txt|max:1024',
                'id_gestion' => 'required|exists:gestion_academica,id'
            ]);

            // Leer archivo CSV
            $archivo = $request->file('archivo_usuarios');
            $contenido = file_get_contents($archivo->getRealPath());
            $lineas = explode("\n", $contenido);
            
            // Remover BOM si existe
            $lineas[0] = preg_replace('/^\xEF\xBB\xBF/', '', $lineas[0]);
            
            // Procesar datos
            $datos = [];
            $errores = [];
            
            // Detectar separador
            $separador = $this->detectarSeparador($lineas[0]);
            $encabezados = str_getcsv($lineas[0], $separador);
            
            // Validar encabezados
            $encabezadosEsperados = ['email', 'name', 'rol', 'codigo_docente', 'telefono'];
            if (count(array_intersect($encabezadosEsperados, $encabezados)) < 3) {
                return redirect()->back()->with('error', 'El archivo no tiene el formato correcto. Los encabezados deben incluir: email, name, rol, codigo_docente, telefono');
            }

            // Procesar cada línea
            for ($i = 1; $i < count($lineas); $i++) {
                if (empty(trim($lineas[$i]))) continue;
                
                $fila = str_getcsv($lineas[$i], $separador);
                if (count($fila) < 3) continue;
                
                $datosFila = array_combine($encabezados, array_pad($fila, count($encabezados), ''));
                
                // Generar password automáticamente
                $datosFila['password'] = $this->generarPassword($datosFila);
                
                // Validar fila
                $errorFila = $this->validarFila($datosFila, $i + 1);
                if ($errorFila) {
                    $errores[] = $errorFila;
                } else {
                    $datos[] = $datosFila;
                }
            }

            if (empty($datos)) {
                return redirect()->back()->with('error', 'No se encontraron datos válidos en el archivo.');
            }

            return view('cargaMasiva.preview', [
                'datos' => $datos,
                'errores' => $errores,
                'totalRegistros' => count($datos),
                'totalErrores' => count($errores),
                'id_gestion' => $request->id_gestion,
                'nombreArchivo' => $archivo->getClientOriginalName()
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    public function procesar(Request $request)
    {
        try {
            $request->validate([
                'datos' => 'required|array',
                'id_gestion' => 'required|exists:gestion_academica,id'
            ]);

            // Decodificar los datos JSON
            $datos = [];
            foreach ($request->datos as $jsonData) {
                $usuarioData = json_decode($jsonData, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $datos[] = $usuarioData;
                }
            }

            $resultados = [
                'exitosos' => 0,
                'errores' => [],
                'usuarios_creados' => []
            ];

            DB::beginTransaction();

            foreach ($datos as $index => $usuarioData) {
                try {
                    // Validar nuevamente antes de crear
                    $error = $this->validarFila($usuarioData, $index + 1);
                    if ($error) {
                        $resultados['errores'][] = "Fila " . ($index + 1) . ": " . $error;
                        continue;
                    }

                    // Crear usuario
                    $usuario = User::create([
                        'name' => $usuarioData['name'],
                        'email' => $usuarioData['email'],
                        'password' => Hash::make($usuarioData['password']),
                        'email_verified_at' => now(),
                    ]);

                    // Asignar rol
                    $usuario->assignRole($usuarioData['rol']);

                    // Si es docente, crear registro en tabla docente
                    if ($usuarioData['rol'] === 'docente' && !empty($usuarioData['codigo_docente'])) {
                        Docente::create([
                            'codigo' => $usuarioData['codigo_docente'],
                            'fecha_contrato' => now(),
                            'sueldo' => 0,
                            'telefono' => $usuarioData['telefono'] ?? null,
                            'id_users' => $usuario->id
                        ]);
                    }

                    $resultados['exitosos']++;
                    $resultados['usuarios_creados'][] = [
                        'email' => $usuario->email,
                        'name' => $usuario->name,
                        'rol' => $usuarioData['rol'],
                        'codigo_docente' => $usuarioData['codigo_docente'] ?? 'N/A',
                        'password_generada' => $usuarioData['password']
                    ];

                } catch (\Exception $e) {
                    $resultados['errores'][] = "Fila " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            return view('cargaMasiva.resultados', [
                'resultados' => $resultados,
                'totalProcesados' => count($datos),
                'id_gestion' => $request->id_gestion
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.carga-masiva.usuarios.index')
                ->with('error', 'Error en el proceso de importación: ' . $e->getMessage());
        }
    }

    public function descargarPlantilla()
    {
        // Usar punto y coma como separador para mejor compatibilidad con Excel
        $contenido = "email;name;rol;codigo_docente;telefono\n";
        $contenido .= "docente1@ficct.edu.bo;Juan Pérez;docente;DOC001;78111662\n";
        $contenido .= "coordinador1@ficct.edu.bo;María López;coordinador;;78111663\n";
        $contenido .= "admin@ficct.edu.bo;Admin Sistema;admin;;78111664\n";
        $contenido .= "docente2@ficct.edu.bo;Carlos Rojas;docente;DOC002;78111665\n";
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="plantilla-usuarios.csv"',
        ];
        
        return response($contenido, 200, $headers);
    }

    private function validarFila($fila, $numeroFila)
    {
        $camposObligatorios = ['email', 'name', 'rol'];
        foreach ($camposObligatorios as $campo) {
            if (empty($fila[$campo] ?? '')) {
                return "Campo '$campo' es obligatorio";
            }
        }

        if (!filter_var($fila['email'], FILTER_VALIDATE_EMAIL)) {
            return "Email no válido: " . $fila['email'];
        }

        if (User::where('email', $fila['email'])->exists()) {
            return "El email ya existe en el sistema: " . $fila['email'];
        }

        $rolesPermitidos = ['admin', 'coordinador', 'docente'];
        if (!in_array($fila['rol'], $rolesPermitidos)) {
            return "Rol no válido. Debe ser: " . implode(', ', $rolesPermitidos);
        }

        if ($fila['rol'] === 'docente') {
            if (empty($fila['codigo_docente'])) {
                return "El código docente es obligatorio para el rol 'docente'";
            }
            
            if (Docente::where('codigo', $fila['codigo_docente'])->exists()) {
                return "El código docente ya existe: " . $fila['codigo_docente'];
            }
        }

        return null;
    }

    private function generarPassword($datosFila)
    {
        if ($datosFila['rol'] === 'docente' && !empty($datosFila['codigo_docente'])) {
            $iniciales = $this->obtenerIniciales($datosFila['name']);
            return $datosFila['codigo_docente'] . $iniciales;
        }
        
        $nombre = preg_replace('/[^a-zA-Z]/', '', $datosFila['name']);
        $iniciales = strtoupper(substr($nombre, 0, 3));
        return $iniciales . '123';
    }

    private function obtenerIniciales($nombreCompleto)
    {
        $palabras = explode(' ', trim($nombreCompleto));
        $iniciales = '';
        
        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                $iniciales .= strtoupper(substr($palabra, 0, 1));
            }
        }
        
        return $iniciales;
    }

    private function detectarSeparador($primeraLinea)
    {
        // Contar ocurrencias de diferentes separadores
        $puntoYComa = substr_count($primeraLinea, ';');
        $coma = substr_count($primeraLinea, ',');
        $tabulador = substr_count($primeraLinea, "\t");
        
        // Usar el separador más común
        if ($puntoYComa > $coma && $puntoYComa > $tabulador) {
            return ';';
        } elseif ($coma > $puntoYComa && $coma > $tabulador) {
            return ',';
        } elseif ($tabulador > 0) {
            return "\t";
        } else {
            return ';'; // Por defecto usar punto y coma
        }
    }
}