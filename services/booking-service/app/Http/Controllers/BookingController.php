<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings, 200);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'field_id'     => 'required|integer',
            'booking_date' => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. KOMUNIKASI INTER-SERVICE
        $response = Http::get("http://localhost:8001/api/fields/" . $request->field_id);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gagal menghubungi Field Service atau Lapangan tidak ditemukan'
            ], 500);
        }

        $fieldData = $response->json();
        
        if (!isset($fieldData['price_per_hour'])) {
            return response()->json(['message' => 'Data harga lapangan tidak valid'], 400);
        }

        $pricePerHour = $fieldData['price_per_hour'];

        // 3. Kalkulasi harga sederhana
        $totalPrice = $pricePerHour * 2; 

        // 4. Simpan ke database booking_service
        $booking = Booking::create([
            'user_id'      => $request->user()->id ?? 1,
            'field_id'     => $request->field_id,
            'booking_date' => $request->booking_date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'total_price'  => $totalPrice,
            'status'       => 'pending'
        ]);

        return response()->json($booking, 201);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        $booking->delete();

        return response()->json(null, 204);
    }
}