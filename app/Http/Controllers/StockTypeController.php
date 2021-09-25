<?php

namespace App\Http\Controllers;

use App\Models\StockType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StockTypeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('stockTypes.index');
  }

  /**
   * Get the data for listing in yajra.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
  public function getStockTypes(Request $request, StockType $stockType)
  {
    $data = $stockType->getData();
    try {
      return DataTables::of($data)
        ->addColumn('Actions', function ($data) {
          return '<button type="button" class="btn btn-success btn-sm" id="getEditStockTypeData" data-id="' . $data->id . '">Editar</button>
                            <button type="button" data-id="' . $data->id . '" class="btn btn-danger btn-sm" id="getDeleteId">Apagar</button>';
        })
        ->rawColumns(['Actions'])
        ->make(true);
    } catch (\Exception $e) {
      print($e);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, StockType $stockType)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }

    $validator = Validator::make($request->all(),
      [
        'name' => 'required|unique:stock_types,name',
      ],
      [
        'name.required' => 'O campo nome é obrigatório.',
        'name.unique' => 'O campo nome é único.',
      ]
    );

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }

    $stockType->storeData($request->all());

    return response()->json(['success' => 'StockType added successfully']);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Models\StockType $stockType
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return redirect()->route('stockTypes.index');
    }
    $stockType = new StockType();

    $stockType = $stockType->findData($id);

    return view('stockTypes.edit', [
      'stock_type' => $stockType,
    ]);
  }

  /**
   * @param Request $request
   * @param StockType $stockType
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, StockType $stockType)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return response()->json(['errors' => ["message" => "Ação não autorizada!"]]);
    }

    $validator = Validator::make($request->all(),
      [
        'name' => 'required|unique:stock_types,name,'.$stockType->id,
      ],
      [
        'name.required' => 'O campo nome é obrigatório.',
        'name.unique' => 'O campo nome é único.',
      ]
    );

    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    }

    $stockType->update($request->all());

    return redirect()->route('stockTypes.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\StockType $stockType
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    $authorize = (new User)->authorizeRoles($request->user('web'), ['admin']);

    if (!$authorize) {
      return redirect()->route('stockTypes.index');
    }
    $stockType = new StockType;
    $stockExists = StockType::with('stocks')->where('id', $id)->first();

    if (!blank($stockExists->stocks)) {
      return response()->json(['errors' => 'Tipo de ativo esta cadastrado em Ativos!']);
    };

    $stockType->deleteData($id);

    return response()->json(['success' => 'Stock type deleted successfully']);
  }

  public function getListStockTypes()
  {
    return StockType::all()->toArray();
  }

}
