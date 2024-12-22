<div class="branch-container" data-branch-id="{{ $branch->id }}" id="branch-{{ $branch->id }}">
    <div class="branch-name">
        <span>Branch: {{ $branch->name }}</span>
        <form action="{{ route('branches.update', $branch->id) }}" method="POST" class="branch-form ajax-form">
            @csrf
            @method('PUT')
            <input type="text" name="name" placeholder="New name" value="{{ $branch->name }}">
            <button type="submit">Update Name</button>
        </form>
        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" style="background-color: #f44336;">Delete Branch</button>
        </form>
    </div>

    <div class="leaves-container">
        <strong>Leaves:</strong>
        @foreach($branch->leaves as $leaf)
            <div class="leaf-item" draggable="true" data-leaf-id="{{ $leaf->id }}">
                <span class="leaf-value">{{ $leaf->value }}</span>
                <form action="{{ route('leaves.update', $leaf->id) }}" method="POST" class="leaf-form ajax-form" style="display: none;" onsubmit="return confirmAction(this, 'edit');">
                    @csrf
                    @method('PUT')
                    <input type="text" name="value" value="{{ $leaf->value }}" required>
                    <button type="submit">Save</button>
                    <button type="button" class="cancel-edit">Cancel</button>
                </form>
                <button class="edit-leaf" onclick="editLeaf(this)">Edit</button>
                <form action="{{ route('leaves.destroy', $leaf->id) }}" method="POST" class="delete-form" style="display:inline;" onsubmit="return confirmDelete(this);">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color: #f44336; color: white;">Delete Leaf</button>
                </form>
                <form action="{{ route('leaves.move', $leaf->id) }}" method="POST" class="move-form" onsubmit="return confirmAction(this, 'move');">
                    @csrf
                    <label for="target_branch">Move to:</label>
                    <select name="target_branch" required>
                        @foreach($branches as $targetBranch)
                            <option value="{{ $targetBranch->id }}">{{ $targetBranch->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Move Leaf</button>
                </form>
            </div>
        @endforeach
    </div>

    <form action="{{ route('branches.addLeaf', $branch->id) }}" method="POST" class="ajax-form">
        @csrf
        <input type="text" name="leaf" placeholder="Add new leaf" required>
        <button type="submit">Add Leaf</button>
    </form>
</div>
