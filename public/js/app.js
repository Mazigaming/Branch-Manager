document.addEventListener('DOMContentLoaded', () => {
    setupFormValidation();
    setupSearch();
    setupDragAndDrop();
    setupVisualFeedback();
});

function setupFormValidation() {
    document.querySelectorAll('.branch-form').forEach(form => {
        form.querySelector('input[name="name"]').addEventListener('input', function() {
            const isValid = this.value.length >= 3;
            this.classList.toggle('invalid', !isValid);
            this.setCustomValidity(isValid ? '' : 'Name must be at least 3 characters');
        });
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm('Are you sure?')) e.preventDefault();
        });
    });
}

function setupSearch() {
    const searchInput = document.querySelector('#branch-search');
    if (!searchInput) return;

    searchInput.addEventListener('input', () => {
        const term = searchInput.value.toLowerCase();
        document.querySelectorAll('.branch-item').forEach(branch => {
            const name = branch.querySelector('.branch-name').textContent.toLowerCase();
            const leaves = Array.from(branch.querySelectorAll('.leaf-item'))
                .map(leaf => leaf.textContent.toLowerCase());

            branch.style.display = name.includes(term) || leaves.some(leaf => leaf.includes(term))
                ? 'block'
                : 'none';
        });
    });
}

function setupDragAndDrop() {
    const leaves = document.querySelectorAll('.leaf-item');
    const branches = document.querySelectorAll('.branch-container');

    leaves.forEach(leaf => {
        leaf.draggable = true;
        leaf.addEventListener('dragstart', e => {
            e.dataTransfer.setData('text/plain', leaf.dataset.leafId);
        });
    });

    branches.forEach(branch => {
        branch.addEventListener('dragover', e => e.preventDefault());
        branch.addEventListener('drop', e => {
            e.preventDefault();
            moveLeaf(e.dataTransfer.getData('text/plain'), branch.dataset.branchId);
        });
    });
}

function moveLeaf(leafId, targetBranchId) {
    fetch(`/leaves/${leafId}/move`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ target_branch: targetBranchId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function setupVisualFeedback() {
    const message = document.querySelector('.success-message, .error-message');
    if (message) {
        message.classList.add('fade-in');
        setTimeout(() => message.classList.add('fade-out'), 3000);
    }

    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', function() {
            this.classList.add('clicked');
            setTimeout(() => this.classList.remove('clicked'), 200);
        });
    });
}
