<?php

namespace App\Http\Controllers\Api;

use App\Models\LogSystem;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class
Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $request;
    protected $column_name = 'usuario_app_id';
    protected $class_name;
    private $usuario_logado;

    //ID do usuário afiliado, vistoriador ou sindico
    private $usuario_tipo_id;
    private $user;
    private $user_type;
    /**
     * Enviar instância de Sindico, Afiliado ou Vistoriador
     * Se o class_name for Sindico, Afiliado ou Vistoriador, então o parametro $super_class_name não é enviado
     */
    protected $super_class_name;

    protected $log;


    public function __construct(Request $request, $class_name, $super_class_name = null)
    {
        $this->request = $request;

        $this->super_class_name = $super_class_name;
        if ($class_name != null) {
            $this->usuario_logado = Auth::guard('api')->user();
            $this->class_name = $class_name;
            $this->usuario_tipo_id = $this->get_id_user_type();
        }
    }

    public function __get($param)
    {
        if ($param == "user" || $param == "usuario_logado") {
            return Auth::guard('api')->user();
        } elseif ($param == "user_type") {
            return Auth::guard('api')->user()->$this->user->tipo;
        } else {
            return $this->$param;
        }
    }

    /**
     * Retorna os dados do perfil do usuário logado (sindico, vistoriador ou afiliado)
     */
    public function dados_usuario_tipo()
    {
        $this->newLog("Visualizando perfil");
        return $this->successResponse('Success', isset($this->usuario_logado->id) ? $this->class_name::where($this->column_name, $this->usuario_logado->id)->orderByDesc("id")->first() : null);
    }

    public function get_id_user_type()
    {
        return isset($this->usuario_logado->id) ? ($this->super_class_name ? $this->super_class_name : $this->class_name)::where($this->column_name, $this->usuario_logado->id)->latest()->value("id") : null;
    }

    public function getValueRequest($request, $obj, $nome_campo, $partial_request = false)
    {
        return isset($request[$nome_campo]) ? (($request[$nome_campo] || $request[$nome_campo] === 0) ? $request[$nome_campo] : (!$partial_request ? "" : null)) : (!$partial_request ? "" : ($obj->$nome_campo ? $obj->$nome_campo : null));
    }

    /**
     * Get a success response
     *
     * @param mix $message
     * @param mix $data
     * @param array $meta
     *
     * @return Illuminate\Http\Response
     */
    public function successResponse($message, $data, array $meta = [])
    {
        // if ($this->log == null) {
        //     $this->newLog("LOG de response sem LOG request");
        // }

        // if ($this->log) {
        //     $this->log->updateResponse($data, $message);
        //     $this->log = null;
        // }
        return response()->json(
            array_merge([
                'data' => $data,
                'message' => $message,
                'success' => true,
                'status' => 200,
            ], $meta),
            200
        );
    }


    protected function newLog($message)
    {
        $this->log = LogSystem::send($message);
    }

    /**
     * Get an error response
     *
     * @param mix $message
     *
     * @return Illuminate\Http\Response
     */
    public function errorResponse($message, $status_code = 422)
    {
        if ($this->log) {
            $this->log->updateResponse(null, $message, $status_code);
            $this->log = null;
        }
        return response()->json([
            'errors' => (array) $message,
            'success' => false,
            'status' => $status_code,
        ], $status_code);
    }

    public function getRequestAttributes(Request $request, $obj)
    {
        foreach ($request as $attr) {
            $obj->$attr = $request[$attr];
        }
        return $obj;
    }

    public function get_object_dao()
    {
        if ($this->super_class_name == null) {
            return $this->class_name::where($this->column_name, $this->usuario_logado->id);
        } elseif ($this->class_name != null) {
            return $this->class_name::where($this->usuario_logado->tipo . "_id", $this->usuario_tipo_id);
        } elseif ($this->super_class_name != null && $this->class_name == null) {
            return $this->super_class_name::where("id", ">", 0);
        }
    }

    public function index()
    {
        try {
            return $this->successResponse('Success', $this->get_object_dao()->get());
        } catch (Exception $e) {
            return $this->errorResponse('Erro ao listar', 404);
        }
    }

    public function show($id = null)
    {
        try {
            if ($this->super_class_name == null) {
                $obj = $this->get_object_dao()->findOrFail($id);
            } else {
                $obj = $this->get_object_dao()->orderByDesc("id")->first();
            }
            return $this->successResponse('Success', $obj);
        } catch (Exception $e) {
            return $this->errorResponse('Erro ao ler', 404);
        }
    }

    public function destroy($id = null)
    {
        try {
            if ($this->super_class_name == null) {
                $obj = $this->get_object_dao()->findOrFail($id);
            } else {
                $obj = $this->get_object_dao()->orderByDesc("id")->first();
            }
            $obj->delete();
            return $this->successResponse('Removido com sucesso', $obj);
        } catch (Exception $e) {
            return $this->errorResponse("Erro ao deletar", 404);
        }
    }
}
