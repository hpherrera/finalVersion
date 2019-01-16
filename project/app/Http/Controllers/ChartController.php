<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Curso;
use App\EstadoProyecto;
use App\TipoProyecto;

class ChartController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function plot(Request $request)
    {
        $modules = $request['modules'];
        $states = $request['states'];
        $types = $request['types'];
        $semesters = $request['semesters'];
        $professors = $request['professors'];
        $date_ini = $request['date_ini'];

        $plot_by = $request['plot_by'];

        $query = \DB::table('proyectos');

        if(!empty($professors) || $plot_by == 3) //3 = profesor guia
        {
            $query = \DB::table('proyectos')
                ->join('personas', 'proyectos.profesorGuia_id', '=', 'personas.id');
        }

        if(!empty($modules))
        {
            $query->whereIn('proyectos.curso_id', $modules);
        }

        if(!empty($states))
        {
            $query->whereIn('proyectos.estado_id', $states);
        }

        if(!empty($types))
        {
            $query->whereIn('proyectos.tipo_id', $types);
        }

        if(!empty($semesters))
        {
            $query->where('proyectos.semestre', '=', $semesters);
        }

        if(!empty($professors))
        {
            $query->whereIn('personas.id', $professors);
        }

        if($date_ini != null && $date_ini != '')
        {
            $query->where('proyectos.year', '=', $date_ini);
        }

        $sel = '';
        $objs = '';

        if($plot_by == 1) //modulo
        {
            $sel = 'curso_id';
            $objs = Curso::all();
        }
        else if($plot_by == 2) //estado
        {
            $sel = 'estado_id';
            $objs = EstadoProyecto::all();
        }
        else if($plot_by == 3) //profesor guia
        {   
            $sel = 'personas.id';
            $objs = \DB::table('personas')
            ->join('proyectos', 'personas.id', '=', 'proyectos.profesorGuia_id')
            ->select('personas.id', 'nombres', 'apellidos')->distinct()
            ->get();
        }
        else if($plot_by == 4) //tipo
        {
            $sel = 'tipo_id';
            $objs = TipoProyecto::all();
        }     

        $ex_query = $this->execute_query($query, $sel);

        $res = $this->group($objs, $ex_query, $sel, $plot_by);

        return \Response::json($res);
    }

    private function execute_query($query, $sel)
    {
        $query->select(\DB::raw('count(proyectos.id) as proyects, 
            proyectos.semestre as semester,'.$sel));
        $query->groupBy('semester');
        $query->groupBy($sel);

        return $query->get();        
    }

    private function group($objs, $query, $sel, $plot_by)
    {
        $datasets = array();
        $data = array();

        foreach($objs as $obj) //for each module
        {
            $ds = array(); //create a dataset

            for ($i = 0; $i < 2; $i++) 
            { 
                if($plot_by == 3)//profesor
                {
                    $sel = 'id';
                }

                $res_1 = $query->where($sel, $obj->id);
                $res_2 = $res_1->where('semester', $i+1);

                if($res_2->isNotEmpty())
                {
                    $ds[$i] = $res_2->first()->proyects;
                }
                else
                {
                    $ds[$i] = 0;
                }
            }

            if($plot_by == 1) //modules
            {
                $data['label'] = ucfirst($obj->nombre);
            }
            else if($plot_by == 2) //estado
            {
                $data['label'] = ucfirst($obj->estado);
            }
            else if($plot_by == 3) //profesor guia
            {
                $data['label'] = ucfirst($obj->nombres).' '.ucfirst($obj->apellidos);
            }
            else if($plot_by == 4) // tipo
            {
                $data['label'] = ucfirst($obj->tipo);
            }

            $data['data'] = $ds;

            array_push($datasets, $data);
        }
        return $datasets;
    }

    public function searchModule(Request $request)
    {
        $term = $request->term;

        $data = Curso::where('nombre', 'like', '%'.$term.'%')->get();

        $response = array();

        foreach($data as $curso)
        {
            $curso_obj = array(
                'id' => $curso->id,
                'name' => ucfirst($curso->nombre)
            );

            array_push($response, $curso_obj);
        }

        return \Response::json($response);
    }

    public function searchState(Request $request)
    {
        $term = $request->term;

        $data = EstadoProyecto::where('estado', 'like', '%'.$term.'%')->get();

        $response = array();

        foreach($data as $estado_proyecto)
        {
            $estado_proyecto = array(
                'id' => $estado_proyecto->id,
                'name' => ucfirst($estado_proyecto->estado)
            );

            array_push($response, $estado_proyecto);
        }

        return \Response::json($response);
    }    

    public function searchType(Request $request)
    {
        $term = $request->term;

        $data = TipoProyecto::where('tipo', 'like', '%'.$term.'%')->get();

        $response = array();

        foreach($data as $tipo)
        {
            $tipo_obj = array(
                'id' => $tipo->id,
                'name' => ucfirst($tipo->tipo)
            );

            array_push($response, $tipo_obj);
        }

        return \Response::json($response);
    }

    public function searchProfessor(Request $request)
    {
        $term = $request->term;

        $data = \DB::table('personas')
        ->join('proyectos', 'personas.id', '=', 'proyectos.profesorGuia_id')
        ->select('personas.id', 'nombres', 'apellidos')->distinct()
        ->whereRaw('CONCAT(nombres," ",apellidos) LIKE "%'.$term.'%"')
        ->get();

        $response = array();

        foreach($data as $persona)
        {
            $persona_obj = array(
                'id' => $persona->id,
                'name' => ucfirst($persona->nombres).' '.ucfirst($persona->apellidos)
            );

            array_push($response, $persona_obj);
        }

        return \Response::json($response);
    }

}
