    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Http\Request;


    //  1. RUTE PUBLIK 
    Route::middleware('throttle:60,1')->group(function () {

        Route::post('/auth/login', function (Illuminate\Http\Request $request) {
            $response = Http::post('http://127.0.0.1:8000/api/login', $request->all());
            
            if ($response->failed()) {
                return response($response->body(), $response->status())
                        ->header('Content-Type', 'text/html'); 
            }

            return $response->json();
        });
        
        Route::get('/auth/github', function () {
            return Http::get(env('AUTH_SERVICE_URL') . '/api/login/github')->json();
        });

        // Proxy ke Field Servic
        Route::get('/fields', function () {
            $response = Http::get(env('FIELD_SERVICE_URL') . '/api/fields');
            return response()->json($response->json(), $response->status());
        });
    });

    //2. RUTE TERPROTEKSI
    Route::middleware(['throttle:60,1', 'jwt.gateway'])->group(function () {
        
        // Proxy ke Booking Service
        Route::post('/bookings', function (Request $request) {
            $response = Http::post(env('BOOKING_SERVICE_URL') . '/api/bookings', $request->all());
            return response()->json($response->json(), $response->status());
        });

        // Proxy ke Auth Service untuk Logout/Me
        Route::get('/auth/me', function (Request $request) {
            $response = Http::withHeaders([
                'Authorization' => $request->header('Authorization')
            ])->get(env('AUTH_SERVICE_URL') . '/api/me');
            return response()->json($response->json(), $response->status());
        });
    });