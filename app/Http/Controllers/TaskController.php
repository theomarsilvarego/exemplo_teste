<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Task API",
 *     version="1.0.0",
 *     description="API para gerenciamento de tarefas"
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Operações relacionadas a tarefas"
 * )
 *
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"title", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Tarefa de exemplo"),
 *     @OA\Property(property="status", type="string", enum={"pendente", "concluída"}, example="pendente"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-09T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-09T12:00:00Z")
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/task",
     *     summary="Listar todas as tarefas",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro no servidor"
     *     )
     * )
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * @OA\Post(
     *     path="/api/task",
     *     summary="Criar uma nova tarefa",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "status"},
     *             @OA\Property(property="title", type="string", example="Tarefa de exemplo"),
     *             @OA\Property(property="status", type="string", enum={"pendente", "concluída"}, example="pendente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Falha na criação"
     *     )
     * )
     */
    public function store(Request $request)
    {
        if(Task::create($request->all()))
        {
            return response()->json([
                'message' => 'Task created successfully'
            ], 201);
        }
        return response()->json([
            'message' => 'Task creation failed'
        ], 404);
    }

    /**
     * @OA\Get(
     *     path="/api/task/{task}",
     *     summary="Mostrar detalhes de uma tarefa",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da tarefa",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada"
     *     )
     * )
     */
    public function show(string $task)
    {
        $task = Task::find($task);
        if($task)
        {
            return $task;
        }
        return response()->json([
            'message' => 'Task not found'
        ], 404);
    }

    /**
     * @OA\Put(
     *     path="/api/task/{task}",
     *     summary="Atualizar uma tarefa",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "status"},
     *             @OA\Property(property="title", type="string", example="Tarefa atualizada"),
     *             @OA\Property(property="status", type="string", enum={"pendente", "concluída"}, example="concluída")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada"
     *     )
     * )
     */
    public function update(Request $request, string $task)
    {
        $task = Task::find($task);
        if($task)
        {
            $task->update($request->all());
            return $task;
        }
        return response()->json([
            'message' => 'Task not found'
        ], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/task/{task}",
     *     summary="Deletar uma tarefa",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa deletada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada"
     *     )
     * )
     */
    public function destroy(string $task)
    {
        if(Task::destroy($task))
        {
            return response()->json([
                'message' => 'Task deleted successfully'
            ], 201);
        }
        return response()->json([
            'message' => 'Task deletion failed'
        ], 404);
    }
}
