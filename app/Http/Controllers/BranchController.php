<?php
namespace App\Http\Controllers;
use App\Models\{Branch, Leaf};
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index() {
        return view('branches.index', ['branches' => Branch::with('leaves')->get()]);
    }

    public function store(Request $request) {
        $branch = Branch::create($request->validate(['name' => 'required|string|max:255']));
        $branch->leaves()->create(['value' => $request->leaf]);
        return back()->with('success', 'Branch created');
    }

    public function update(Request $request, $id) {
        Branch::findOrFail($id)->update($request->validate(['name' => 'required|string|max:255']));
        return back()->with('success', 'Branch updated');
    }

    public function destroy($id) {
        Branch::findOrFail($id)->delete();
        return back()->with('success', 'Branch deleted');
    }

    public function addLeaf(Request $request, $id) {
        Branch::findOrFail($id)->leaves()->create($request->validate(['value' => 'required|string|max:255']));
        return back()->with('success', 'Leaf added');
    }

    public function deleteLeaf($id) {
        Leaf::findOrFail($id)->delete();
        return back()->with('success', 'Leaf deleted');
    }

    public function moveLeaf(Request $request, $id) {
        Leaf::findOrFail($id)->update(['branch_id' => $request->target_branch]);
        return back()->with('success', 'Leaf moved');
    }
}
