@extends('layout.app')
@section('content')
    <style>
        body {
            background-color: var(--latte-base);
            color: var(--latte-text);
            font-family: Arial, sans-serif;
        }

        h2 {
            color: var(--latte-primary);
            margin-bottom: 1rem;
        }

        h3 {
            color: var(--latte-dark);
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--latte-light);
            border: 1px solid var(--latte-subtle);
        }

        th, td {
            padding: 0.5rem;
            border: 1px solid var(--latte-subtle);
            text-align: left;
        }

        th {
            color: var(--latte-dark);
        }

        td {
            color: var(--latte-input-text);
        }

        .row-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            border: 1px solid var(--latte-subtle);
            border-radius: 6px;
            background-color: var(--latte-light);
            margin-bottom: 0.5rem;
        }

        .csv-column {
            font-weight: bold;
            color: var(--latte-dark);
            flex: 1;
        }

        .dropdown {
            flex: 2;
        }

        select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--latte-subtle);
            border-radius: 4px;
            background-color: var(--latte-input-bg);
            color: var(--latte-input-text);
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 4px;
            transition: background-color 0.2s, transform 0.2s;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--latte-primary);
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background-color: #5f81d7;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background-color: var(--latte-secondary);
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background-color: #84c97a;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-matching {
            background-color: var(--latte-dark);
            color: var(--latte-light);
            border: 1px solid var(--latte-dark);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-matching:hover {
            background-color: #1F2A35;
            color: var(--latte-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .link-secondary {
            color: var(--latte-muted);
            text-decoration: underline;
            transition: color 0.2s;
        }

        .error-message {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .link-secondary:hover {
            color: var(--latte-dark);
        }
    </style>

    <div class="p-6 bg-latte-base text-latte-text">
        <h2 class="text-2xl mb-4">Preview Import</h2>
        <div class="mb-5">
            <h3 class="text-lg mb-2">Sample Data:</h3>
            <div class="overflow-x-auto bg-latte-light border border-latte-subtle rounded">
                <table class="min-w-full border">
                    <thead>
                    <tr>
                        @foreach($headers as $header)
                            <th class="border p-2">{{ $header }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($firstRow as $value)
                            <td class="border p-2">{{ $value }}</td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <form action="{{ route('import.process') }}" method="POST" id="importForm">
            @csrf
            <div class="mb-4">
                <button type="button" id="autoMatchBtn" class="btn btn-matching mb-4">Auto-Match Columns</button>
            </div>
            <div class="mb-6">
                @foreach($headers as $header)
                    <div class="row-container">
                        <div class="csv-column">CSV Column: {{ $header }}</div>
                        <div class="dropdown">
                            <select name="column_mapping[{{ $header }}]" class="column-mapping">
                                <option value="">Don't Import</option>
                                @foreach($headers as $value => $label)
                                    <option value="{{ $label }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>
            <input type="hidden" name="filename" value="{{ $filename }}">
            <input type="hidden" name="action" id="actionType" value="import">
            <button type="submit" class="btn btn-primary me-3">Import</button>
            <button type="submit" class="btn btn-secondary me-3" onclick="setAction('import_and_close')">
                Import and Close
            </button>
            <span>Or</span>
            <a href="{{ route('receipts') }}" class="link-secondary">Cancel</a>
        </form>
    </div>
    <script>
        function setAction(action) {
            document.getElementById('actionType').value = action;
        }

        document.getElementById('autoMatchBtn').addEventListener('click', function () {
            const columnMappings = document.querySelectorAll('.column-mapping');
            columnMappings.forEach((dropdown) => {
                const csvColumn = dropdown.name.match(/column_mapping\[(.*?)\]/)[1];
                let foundMatch = false;
                dropdown.querySelectorAll('option').forEach((option) => {
                    if (option.textContent.trim().toLowerCase() === csvColumn.trim().toLowerCase()) {
                        option.selected = true;
                        foundMatch = true;
                    }
                });
                if (!foundMatch) {
                    dropdown.value = '';
                }
            });
        });

        document.getElementById('importForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const columnMappings = document.querySelectorAll('.column-mapping');
            let hasError = false;

            // Remove any existing error messages
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            columnMappings.forEach((dropdown) => {
                const csvColumn = dropdown.name.match(/column_mapping\[(.*?)\]/)[1];
                const selectedValue = dropdown.value;

                if (selectedValue && selectedValue !== csvColumn) {
                    hasError = true;
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                    errorDiv.textContent = `Selected value must match column header "${csvColumn}"`;
                    dropdown.parentNode.appendChild(errorDiv);
                    dropdown.style.borderColor = 'red';
                } else {
                    dropdown.style.borderColor = '';
                }
            });

            if (!hasError) {
                this.submit();
            }
        });
    </script>
@endsection
