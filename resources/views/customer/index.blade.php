<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="api-token" content="{{ session('api_token') }}">

    <title>OAuth2 API App</title>

    <!-- Styles -->
    <style>
        body {
            background-color: #383838;
            color: #fff;
        }
        .customers_table thead tr th {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .customers_table tbody tr td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div>
        <h1>Customers</h1>
        <!-- Logout Button -->
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="logout-btn">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

        <!-- Create Customer Form -->
        <div class="form-container">
            <h2>Add New Customer</h2>
            <form id="frm_customer_create">
                @csrf
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="number" name="age" placeholder="Age" required>
                <input type="date" name="dob" placeholder="Date of Birth" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Add Customer</button>
            </form>
        </div>

        <div class="table-container">
            <h2>List</h2>
            <table class="customers_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Action(s)</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
                    @foreach($customers as $customer)
                        <tr id="customer-{{ $customer->id }}">
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->first_name }}</td>
                            <td>{{ $customer->last_name }}</td>
                            <td>{{ $customer->age }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <button onclick="openModalCustomerUpdate({{ $customer }})">Edit</button>
                                <button onclick="deleteCustomer({{ $customer->id }})">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="modal_customer_update" style="display:none;">
                <h2>Edit Customer</h2>
                <form id="frm_customer_update">
                    <input type="hidden" id="edit_customer_id">
                    <input type="text" name="first_name" id="edit_first_name" required>
                    <input type="text" name="last_name" id="edit_last_name" required>
                    <input type="number" name="age" id="edit_age" required>
                    <input type="date" name="dob" id="edit_dob" required>
                    <input type="email" name="email" id="edit_email" required>
                    <button type="submit">Update Customer</button>
                    <button type="button" onclick="closeModalCustomerUpdate()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>

        const apiToken = document.querySelector('meta[name="api-token"]').getAttribute('content');

        document.getElementById('frm_customer_create').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            const res = await fetch('/api/customer', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json', 
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            if (res.ok) {
                location.reload(); // Reload to update the customer list
            } else {
                const resData = await res.json();
                alert(resData.message || 'Error adding customer');
            }
        });

        async function deleteCustomer(id) {
            const res = await fetch(`/api/customer/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            if (res.ok) {
                document.getElementById(`customer-${id}`).remove();
                alert('Customer deleted successfully.');
            } else {
                const resData = await res.json();
                alert(resData.message || 'Error deleting customer');
            }
        }

        function openModalCustomerUpdate(customer) {
            document.getElementById('edit_customer_id').value = customer.id;
            document.getElementById('edit_first_name').value = customer.first_name;
            document.getElementById('edit_last_name').value = customer.last_name;
            document.getElementById('edit_age').value = customer.age;
            document.getElementById('edit_dob').value = customer.dob;
            document.getElementById('edit_email').value = customer.email;
            document.getElementById('modal_customer_update').style.display = 'block';
        }

        document.getElementById('frm_customer_update').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_customer_id').value;
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            const res = await fetch(`/api/customer/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json', 
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (res.ok) {
                location.reload(); // Reload to update the customer list
            } else {
                const resData = res.json();
                alert(resData.message || 'Error updating customer');
            }
        });

        function closeModalCustomerUpdate() {
            document.getElementById('modal_customer_update').style.display = 'none';
            document.getElementById('frm_customer_update').reset();
        }
    </script>
</body>
</html>