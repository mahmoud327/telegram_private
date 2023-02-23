<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Question;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materials = Material::latest()->paginate(10);
        return view('admin.materials.index', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $material=Material::create($request->all());

    //    $telegram= Telegram::sendMessage([
    //         'chat_id'=>'-849059038',
    //         'text'=>
    //         "title:".$question->title."\n".
    //         "question:".$question->question
    //      ]);
        session()->flash('Add', 'تم اضافة سجل بنجاح ');
        return redirect()->back();
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
        $material = Material::findOrFail($id);
        $material->update($request->all());
        session()->flash('edit', 'تم اضافة سجل بنجاح ');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();
        session()->flash('delete', 'تم حذف سجل بنجاح ');
        return redirect()->back();
    }
}
