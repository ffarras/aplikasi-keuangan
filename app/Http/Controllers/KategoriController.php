<?php
 
namespace App\Http\Controllers;
          
use App\Kategori;
use Illuminate\Http\Request;
use DataTables;
        
class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kategori = Kategori::all();
        if ($request->ajax()) {
            $data = Kategori::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn ('action', function ($row) { 
                        $btn = '<a data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-warning btn-xs editKategori"><i class="icon-note"></i></a>';
                        $btn = $btn.' <a data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-xs deleteKategori"><i class="icon-trash"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('kategori.index', compact('kategori'));
    }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|regex:/^[A-Za-z ]+$/',
        ]);

        Kategori::updateOrCreate(
            ['id' => $request->kategori_id],
            ['kategori' => $request->kategori]
        );        
   
        return response()->json(' saved');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = Kategori::find($id);
        return response()->json($kategori);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Kategori::find($id)->delete();
     
        return response()->json(['success'=>'Kategori berhasil dihapus.']);
    }
}