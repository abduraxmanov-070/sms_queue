<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = date('Y-m-d', strtotime(now()));
        $tomorrow = date('Y-m-d',strtotime('+1 day', strtotime(now())));
        $after_tomorrow = date('Y-m-d',strtotime('+2 day',strtotime(now())));
//        dd($after_tomorrow);
        $customer_today = Customer::orderby('time')->where('date',$today)->get();
        $customer_tomorrow = Customer::orderby('time')->where('date',$tomorrow)->get();
        $customer_after_tomorrow = Customer::orderby('time')->where('date',$after_tomorrow)->get();
//        dd($customer_after_tomorrow);
        $date = [];
        foreach ($customer_today as $value)
        $date['today'][] = $value;
        foreach ($customer_tomorrow as $value)
        $date['tomorrow'][] = $value;
        foreach ($customer_after_tomorrow as $value)
        $date['after_tomorrow'][] = $value;
        return response()->json($date, 200)->setCharset('utf-8');
//        $customers = Customer::orderby('date')->orderby('time')->get();
//        return $customers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = date($request['date']);
        $time = date($request['time']);
        $doctor = $request['doctor_id'];
//        $from_date = date('y-m-d', strtotime('-30 minute', strtotime($date)));
//        $to_date = date('y-m-d', strtotime('+30 minute', strtotime($date)));
        $from_time = date('H:i', strtotime('-30 minute', strtotime($time)));
        $to_time = date('H:i', strtotime('+30 minute', strtotime($time)));
        $customer = Customer::where('doctor_id', $doctor)->where('date', $date)->whereBetween('time', [$from_time, $to_time])->get();
//        dd($customer);
//        return response()->json($date." ".$time."\n".now());
        if (strtotime($date.$time) < strtotime(now())) {
            return response()->json(['message' => 'Bu vaqt o\'tib ketgan !'], 404);
        } else
            if ($customer->count() > 0)
                return response()->json(['message' => 'Shifokorning bu vaqtda mijozi bor !'], 404);
            else {
                $customer = new Customer();
                $customer->create($request->all());
                return response()->json(['message' => "Ro'yxatga yozildingiz !"], 200);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        dd($id);
//        $customer = Customer::find($id);
//        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $date = date($request['date']);
        $time = date($request['time']);
        $doctor = $request['doctor_id'];
//        $from_date = date('y-m-d', strtotime('-30 minute', strtotime($date)));
//        $to_date = date('y-m-d', strtotime('+30 minute', strtotime($date)));
        $from_time = date('H:i', strtotime('-30 minute', strtotime($time)));
        $to_time = date('H:i', strtotime('+30 minute', strtotime($time)));
        $customer = Customer::where('doctor_id', $doctor)->where('date', $date)->whereBetween('time', [$from_time, $to_time])->get();
//        dd($customer);
        if (strtotime($date.$time) < strtotime(now())) {
            return response()->json(['message' => 'Bu vaqt o\'tib ketgan !'], 404);
        } else
            if ($customer->count() > 0)
                return response()->json(['message' => 'Shifokorning bu vaqtda mijozi bor !'], 404);
            else {
                $customer = Customer::find($id);
                $customer->update($request->all());
                return response()->json(['message' => "Ro'yxatga yozildingiz !"], 200);
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::find($id)->delete();
        return response()->json(['message' => "o'chirildi"], 200);
    }
}
