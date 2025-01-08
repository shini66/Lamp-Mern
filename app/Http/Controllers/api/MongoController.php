<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\UTCDateTime;
use DateTime;
use Illuminate\Support\Facades\Auth;

class MongoController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => $user->name,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function products()
    {
        $result = DB::connection('mongodb')->table('productos')->get();
        return response()->json($result);
    }

    public function users()
    {
        $result = DB::connection('mongodb')->table('usuarios')->get();
        return response()->json($result);
    }

    public function sales()
    {
        $result = DB::connection('mongodb')->table('ventas')->get();
        return response()->json($result);
    }

    public function groupSales()
    {
        $result = DB::connection('mongodb')
            ->getMongoDB() // Obtén la instancia del cliente MongoDB
            ->ventas // Nombre de la colección
            ->aggregate([ // Consulta de agregación
                [
                    '$group' => [
                        '_id'         => '$producto', // Agrupar por producto
                        'totalVentas' => ['$sum' => '$cantidad'], // Sumar las cantidades
                        'ingresos'    => ['$sum' => ['$multiply' => ['$cantidad', '$precio']]], // Sumar ingresos multiplicando cantidad por precio
                    ],
                ],
            ]);

        $data = iterator_to_array($result);

        return response()->json($data);
    }

    public function filterAgeUser(Request $request)
    {
        $result = DB::connection('mongodb')
            ->getMongoDB() // Obtén la instancia del cliente MongoDB
            ->usuarios // Nombre de la colección
            ->aggregate([ // Consulta de agregación
                [
                    '$match' => [
                        'edad' => [ '$gt' => (int)$request->age ] 
                    ]
                ],
            ]);

        $data = iterator_to_array($result);

        $newData = [
            'status' => 'success',
            'count' => count($data),
            'data' => $data
        ];

        return response()->json($newData);
    }

    function filterUser(Request $request)
    {
        
        $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        
        $result = DB::connection('mongodb')
            ->getMongoDB() // Obtén la instancia del cliente MongoDB
            ->usuarios // Nombre de la colección
            ->aggregate([ // Consulta de agregación
                [
                    '$match' => [
                        'activo' => $status
                    ],
                ],
                [
                    '$addFields' => [
                        'esMayorDeEdad' => [ '$gte' => ['$edad', (int)$request->age] ]
                    ]
                ]
            ]);

        $data = iterator_to_array($result);

        $newData = [
            'status' => 'success',
            'count' => count($data),
            'data' => $data
        ];

        return response()->json($newData);
    }

    public function searchSales(Request $request){

        $date = $request->date;

        $dateFilter = new UTCDateTime(new DateTime($date));
        
        $result = DB::connection('mongodb')
            ->getMongoDB() // Obtén la instancia del cliente MongoDB
            ->ventas // Nombre de la colección
            ->aggregate([ // Consulta de agregación
                [
                    '$match' => [
                        'fecha' => [
                            '$gte' => $dateFilter
                        ],
                    ],
                ],
                [
                    '$sort' => [
                        'fecha' => -1
                    ]
                ]
            ]);

        $data = iterator_to_array($result);

        return response()->json($data);

    }

}
