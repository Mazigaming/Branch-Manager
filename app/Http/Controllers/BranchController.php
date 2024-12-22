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
        $branch = Branch::create(['name' => $request->name]);
        $branch->leaves()->create(['value' => $request->leaf]);
        return back()->with('success', 'Done');
    }

    public function update(Request $request, $id) {
        Branch::findOrFail($id)->update(['name' => $request->name]);
        return back()->with('success', 'Done');
    }

    public function destroy($id) {
        Branch::findOrFail($id)->delete();
        return back()->with('success', 'Done');
    }

    public function addLeaf(Request $request, $id) {
        Branch::findOrFail($id)->leaves()->create(['value' => $request->leaf]);
        return back()->with('success', 'Done');
    }

    public function deleteLeaf($id) {
        Leaf::findOrFail($id)->delete();
        return back()->with('success', 'Done');
    }

    public function moveLeaf(Request $request, $id) {
        Leaf::findOrFail($id)->update(['branch_id' => $request->target_branch]);
        return back()->with('success', 'Done');
    }
}
