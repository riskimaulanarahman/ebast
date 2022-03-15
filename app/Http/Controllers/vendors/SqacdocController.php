<?php

namespace App\Http\Controllers\vendors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Sqac;
use App\Sqacapprover;
use App\Sqacattachment;
use App\Approver;
use DB;


class SqacdocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Sqac::all();

            return response()->json(['status' => "show", "message" => "Menampilkan Data" , 'data' => $data]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $requestData = $request->all();

        try {
            $sqac = Sqac::create($requestData);

            $getappr = Approver::all();
            foreach($getappr as $datas) {
                $sqacappr['sqac_id'] = $sqac->id;
                $sqacappr['approver_id'] = $datas->id;
                $sqacappr['approverstatus'] = 0;

                $addappr = Sqacapprover::create($sqacappr);

            }

            return response()->json(["status" => "success", "message" => "Berhasil Menambahkan Data"]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('pages/vendors/sqacdoc');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $date = $request->submitted_date;
        $fixed = date('Y-m-d', strtotime(substr($date,0,10)));

        $requestData = $request->all();
        if($date) {
            $requestData['submitted_date'] = $fixed;
        }
        
        try {
            $data = Sqac::findOrFail($id);
            $data->update($requestData);

            return response()->json(["status" => "success", "message" => "Berhasil Ubah Data"]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Sqac::where('id',$id)->delete();

            return response()->json(["status" => "success", "message" => "Berhasil Hapus Data"]);

        } catch (\Exception $e){

            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function getattachment(Request $request)
    {
        $data = Sqacattachment::where('sqac_id',$request->id)->get();

        return response()->json(['status' => "show", "message" => "Menampilkan Detail" , 'data' => $data]);
        
    }

    public function getapproverlist(Request $request)
    {
        $data = DB::table('vwapproverlist')->where('sqac_id',$request->id)->get();

        return response()->json(['status' => "show", "message" => "Menampilkan Detail" , 'data' => $data]);
        
    }
}
