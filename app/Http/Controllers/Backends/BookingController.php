<?php

namespace App\Http\Controllers\Backends;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\helpers\GlobalFunction;
use App\Models\Product;
use App\Models\ProductDate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BookingController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $product_dates = ProductDate::all();
        return view('backends.booking.index', compact('products', 'product_dates'));
    }
    public function loadDates(Request $request)
    {

        // $request->validate([
        //     'id' => 'required',
        //     'start_date' => 'required',
        //     'end_date' => 'required',
        // ]);

        $product = Product::find($request->query('id'));
        if (empty($product)) {
            return $this->sendError(__('Product not found'));
        }
        $query = ProductDate::query();
        $query->where('product_id', $request->query('id'));
        $query->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($request->query('start'))));
        $query->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($request->query('end'))));

        $rows =  $query->take(100)->get();

        $allDates = [];

        $period = GlobalFunction::periodDate($request->input('start'), $request->input('end'), false);

        foreach ($period as $dt) {
            $date = [
                'id' => rand(0, 999),
                'is_active' => 0,
                'price' => $product->price ?? 0,
                'number' => $product->number_available,
                'is_instant' => 0,
                'is_default' => true,
                'textColor' => '#2791fe'
            ];
            // $date['price_html'] = format_money($date['price']);
            $date['price_html'] =  $date['number'];
            $date['title'] = $date['event']  = $date['price_html'];
            $date['start'] = $date['end'] = $dt->format('Y-m-d');
            $date['is_active'] = 1;
            // $total_number = $date['number'];
            $allDates[$dt->format('Y-m-d')] = $date;
        }
        // \Log::info($allDates);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $row->start = date('Y-m-d', strtotime($row->start_date));
                $row->end = date('Y-m-d', strtotime($row->start_date));
                $row->textColor = '#2791fe';
                $number = $row->number ?? 0;
                if (empty($number)) {
                    $number = $product->number_available;
                }
                // $row->title = $row->event = format_money($number);
                $row->title = $row->event = $number;
                $row->number = $number;
               
                if (!$row->is_active) {
                    $row->title = $row->event = __('Close');
                    $row->backgroundColor = '#fe2727';
                    $row->classNames = ['blocked-event'];
                    $row->textColor = '#fe2727';
                    $row->is_active = 0;
                } else {
                    $row->classNames = ['active-event'];
                    $row->is_active = 1;
                    // if($row->is_instant){
                    //     $row->title = '<i class="fa fa-bolt"></i> '.$row->title;
                    // }
                }
                $row->total_number = $row->number;
                $allDates[date('Y-m-d', strtotime($row->start_date))] = $row->toArray();
            }   
        }

        // dd($request->query('start'), $request->query('end'));
        // $bookings = $room->getBookingsInRange($request->query('start'),$request->query('end'));
        // // dd($bookings->count());
        // if($bookings->count() > 0 && !empty($bookings))
        // {
        //     foreach ($bookings as $booking){
        //         $booking_end_date = Carbon::parse($booking->end_date);
        //         $period = GlobalFunction::periodDate($booking->start_date,$booking_end_date,false);
        //         foreach ($period as $dt){
        //             $date = $dt->format('Y-m-d');
        //             if(isset($allDates[$date])){
        //                 $allDates[$date]['total_number'] = $allDates[$date]['total_number'] ?? $total_number;
        //                 $allDates[$date]['number'] -= $booking->number;
        //                 $allDates[$date]['event'] = $allDates[$date]['title'] = '$'. $allDates[$date]['price'] . ' x '.$allDates[$date]['number'];

        //                 if($allDates[$date]['number'] <= 0){
        //                     $allDates[$date]['active'] = 0;
        //                     $allDates[$date]['event'] = __('Full Book');
        //                     $allDates[$date]['title'] = __('Full Book');
        //                     $allDates[$date]['classNames'] = ['full-book-event'];
        //                 }
        //             }
        //         }
        //         // $allDates[$date]['booking_number'] = $booking->sum('number');
        //     }
        // }
        $data = array_values($allDates);
        // dd($data);
        // \Log::info($data);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            // 'product_id' => 'required|exists:products,id', 
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date|after_or_equal:start_date',
            // 'number' => 'required|integer',
            // 'price' => 'nullable|numeric', 

        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput()->with(['success' => 0, 'msg' => __('Invalid form input')]);
        }
        try {
            DB::beginTransaction();

            // Check if a record already exists with the same id, start_date, and end_date
            $product_date = ProductDate::where('product_id', $request->target_id)
                ->where('start_date', $request->start_date)
                ->where('end_date', $request->end_date)
                ->where('id', '!=', $request->id)
                ->first();


            if ($product_date) {
                // Update existing record
                $product_date->number = $request->number;
                $product_date->is_active = $request->is_active ?? 0;
                $product_date->price = $request->price;
                $message = __('Updated Successfully!');
            } else {
                // Create new record
                $product_date = new ProductDate;
                $product_date->product_id = $request->target_id;
                $product_date->start_date = $request->start_date;
                $product_date->end_date = $request->end_date;
                $product_date->number = $request->number;
                $product_date->is_active = $request->is_active;
                $product_date->price = $request->price;
                $message = __('Created Successfully!');
            }

            $product_date->save();

            DB::commit();
            $output = ['success' => 1, 'msg' => $message];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product Date save error: ' . $e->getMessage());
            $output = ['danger' => 0, 'msg' => __('Something went wrong!' . $e->getMessage())];
        }

        return response()->json($output);
    }
}
