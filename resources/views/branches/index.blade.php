<!DOCTYPE html>
<html>
<head>
    <title>Branch Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html, body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            padding: 2rem;
            background: radial-gradient(125% 125% at 50% 10%, #fff 40%, #63e 100%) fixed no-repeat;
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .search-box {
            width: 100%;
            max-width: 400px;
            padding: 0.8rem;
            margin: 1rem auto;
            display: block;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .search-box:focus {
            border-color: #63e;
            outline: none;
        }

        .create-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .create-form input {
            padding: 0.8rem;
            margin-right: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .branch-list {
            list-style: none;
            padding: 0;
        }

        .branch-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .branch-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .branch-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .leaf-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 1rem 0;
        }

        .leaf-item {
            background: white;
            padding: 0.8rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        button {
            padding: 0.8rem 1.2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: transform 0.2s, opacity 0.2s;
        }

        button:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .primary-button {
            background: #63e;
            color: white;
        }

        .update-button {
            background: #4CAF50;
            color: white;
        }

        .delete-button {
            background: #f44336;
            color: white;
        }

        select {
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Branch Manager</h1>
            @if(session('success'))
                <div class="message success-message">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="message error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <input type="text" id="branch-search" class="search-box" placeholder="Search branches and leaves...">

        <form action="{{ route('branches.store') }}" method="POST" class="create-form">
            @csrf
            <input type="text" name="name" placeholder="Branch name" required>
            <input type="text" name="leaf" placeholder="First leaf" required>
            <button type="submit" class="primary-button">Create Branch</button>
        </form>

        <ul class="branch-list">
            @foreach ($branches as $branch)
                <li class="branch-card">
                    <div class="branch-header">
                        <span class="branch-name">{{ $branch->name }}</span>
                        <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $branch->name }}">
                            <button type="submit" class="update-button">Update</button>
                        </form>
                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                    </div>

                    <div class="leaf-container">
                        @foreach($branch->leaves as $leaf)
                            <div class="leaf-item">
                                <span>{{ $leaf->value }}</span>
                                <form action="{{ route('leaves.destroy', $leaf->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button">Delete</button>
                                </form>
                                <form action="{{ route('leaves.move', $leaf->id) }}" method="POST">
                                    @csrf
                                    <select name="target_branch" required>
                                        <option value="">Move to...</option>
                                        @foreach($branches as $targetBranch)
                                            @if($targetBranch->id !== $branch->id)
                                                <option value="{{ $targetBranch->id }}">
                                                    {{ $targetBranch->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="submit" class="primary-button">Move</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('branches.addLeaf', $branch->id) }}" method="POST">
                        @csrf
                        <input type="text" name="leaf" placeholder="Add new leaf" required>
                        <button type="submit" class="primary-button">Add Leaf</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
