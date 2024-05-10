<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    function __construct(
        Category $category
    )
    {
        $this->middleware('permission:category-list', ['only' => ['index']]);
        $this->middleware('permission:category-create', ['only' => ['create','simpan']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['hapus']]);
        $this->category = $category;
    }

    public function index()
    {
        $data['categories'] = $this->category->all();
        return view('category.index',$data);
    }

    public function create()
    {
        return view('category.create');
    }

    public function simpan(Request $request)
    {
        $rules = [
            'name'  => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Kategori wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            // $input = $request->all();
            $input['slug'] = Str::slug($request->name);
            $input['name'] = $request->name;
            $category = $this->category->create($input);

            if($category){
                return redirect()->route('category')
                ->with('success','Category '.$input['name'].' created successfully');
            }
        }
        return redirect()->route('category.create')
                ->with(['error' => $validator->errors()->all()]);
    }

    public function edit($id)
    {
        $data['category'] = $this->category->find($id);
        if (empty($data['category'])) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }
        return view('category.edit',$data);
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'name'  => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Kategori wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            $category = $this->category->find($id);
            $input['slug'] = Str::slug($request->name);
            $input['name'] = $request->name;
            $category->update($input);

            if($category){
                return redirect()->route('category')
                ->with('success','Category '.$input['name'].' Update Successfully');
            }
        }
        return redirect()->route('category.edit',['id' => $id])
                ->with(['error' => $validator->errors()->all()]);
    }

    public function delete($id)
    {
        $category = $this->category->find($id);
        if (empty($category)) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }
        $category->delete();
        return redirect()->route('category')->with(['success' => 'Category Deleted Successfully']);
    }
}
