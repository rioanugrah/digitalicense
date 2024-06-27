<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\CategoryDetail;
use Validator;

class CategoryController extends Controller
{
    function __construct(
        Category $category,
        CategoryDetail $category_detail
    )
    {
        $this->middleware('permission:category-list', ['only' => ['index']]);
        $this->middleware('permission:category-create', ['only' => ['create','simpan']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['hapus']]);
        $this->category = $category;
        $this->category_detail = $category_detail;
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

    public function category_detail($id,$slug)
    {
        $data['category'] = $this->category->where('id',$id)->where('slug',$slug)->first();
        if (empty($data['category'])) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }

        return view('category.detail.index',$data);
    }

    public function category_detail_create($id,$slug)
    {
        $data['category'] = $this->category->where('id',$id)->where('slug',$slug)->first();
        if (empty($data['category'])) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }
        return view('category.detail.create',$data);
    }

    public function category_detail_simpan(Request $request,$id,$slug)
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
            $input['category_id'] = $id;
            $input['name'] = $request->name;
            $category_detail = $this->category_detail->create($input);

            if($category_detail){
                return redirect()->route('category.category_detail',['id' => $id, 'slug' => $slug])
                ->with('success','Category '.$input['name'].' created successfully');
            }
        }
        return redirect()->route('category.category_detail_create',['id' => $id, 'slug' => $slug])
                ->with(['error' => $validator->errors()->all()]);
    }

    public function category_detail_edit($id,$slug,$id_category)
    {
        $data['category'] = $this->category->with('category_detail')
                                            ->whereHas('category_detail', function($cd) use($id_category){
                                                $cd->where('id',$id_category);
                                            })
                                            ->where('id',$id)
                                            ->where('slug',$slug)
                                            ->first();
                                            // dd($data);
        if (empty($data['category'])) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }
        return view('category.detail.edit',$data);
    }

    public function category_detail_update(Request $request,$id,$slug,$id_category)
    {
        $category = $this->category->with('category_detail')
                                    ->whereHas('category_detail', function($cd) use($id_category){
                                        $cd->where('id',$id_category);
                                    })
                                    ->where('id',$id)
                                    ->where('slug',$slug)
                                    ->first();
                                    // dd($category);
        if (empty($category)) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }

        $category->category_detail->update([
            'name' => $request->name
        ]);

        if ($category->category_detail) {
            return redirect()->route('category.category_detail',['id' => $category->id, 'slug' => $category->slug])
            ->with('success', 'Kategori Detail Berhasil Diupdate');
        }
        return redirect()->back()->with(['error' => 'Gagal Update']);
    }
}
