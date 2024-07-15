<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Shipping;
use App\Models\Payment;

class AdminController extends Controller
{
    // Show the admin dashboard
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        $products = Product::all();
        $orders = Order::all();
        return view('admin.dashboard', compact('users', 'roles', 'permissions', 'products', 'orders'));
    }

    // Manage Users
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $user->assignRole($validatedData['role_id']);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update($validatedData);

        $user->syncRoles($validatedData['role_id']);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    // Manage Roles
    public function roles()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function createRole()
    {
        return view('admin.roles.create');
    }

    public function storeRole(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
        ]);

        Role::create($validatedData);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function editRole($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    public function updateRole(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
        ]);

        $role = Role::findOrFail($id);
        $role->update($validatedData);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    // Manage Permissions
    public function permissions()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function createPermission()
    {
        return view('admin.permissions.create');
    }

    public function storePermission(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        Permission::create($validatedData);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function updatePermission(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update($validatedData);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }

    // Seller functionalities extended to admin
    public function products()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'listed' => 'required|boolean',
        ]);

        $product = new Product($validatedData);
        $product->save();

        // Add to inventory as well
        Inventory::create([
            'product_id' => $product->id,
            'quantity' => $validatedData['stock_quantity'],
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'listed' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validatedData);

        // Update inventory as well
        $inventory = Inventory::where('product_id', $product->id)->first();
        if ($inventory) {
            $inventory->update(['quantity' => $validatedData['stock_quantity']]);
        } else {
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => $validatedData['stock_quantity'],
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // Delete from inventory as well
        Inventory::where('product_id', $product->id)->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    // Manage orders
    public function orders()
    {
        $orders = Order::all();
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Manage inventory
    public function inventory()
    {
        $inventory = Inventory::all();
        return view('admin.inventory.index', compact('inventory'));
    }

    public function addToInventory()
    {
        $products = Product::all();
        return view('admin.inventory.create', compact('products'));
    }

    public function storeInventory(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::updateOrCreate(
            ['product_id' => $validatedData['product_id']],
            ['quantity' => $validatedData['quantity'], 'location' => $validatedData['location']]
        );

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory added/updated successfully.');
    }

    public function editInventory($id)
    {
        $inventory = Inventory::findOrFail($id);
        $products = Product::all();
        return view('admin.inventory.edit', compact('inventory', 'products'));
    }

    public function updateInventory(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->update($validatedData);

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory updated successfully.');
    }

    public function deleteInventory($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory deleted successfully.');
    }

    // Manage shipping
    public function shipping()
    {
        $shipping = Shipping::all();
        return view('admin.shipping.index', compact('shipping'));
    }

    public function createShipping()
    {
        return view('admin.shipping.create');
    }

    public function storeShipping(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $shipping = new Shipping($validatedData);
        $shipping->save();

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping created successfully.');
    }

    public function editShipping($id)
    {
        $shipping = Shipping::findOrFail($id);
        return view('admin.shipping.edit', compact('shipping'));
    }

    public function updateShipping(Request $request, $id)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $shipping = Shipping::findOrFail($id);
        $shipping->update($validatedData);

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping updated successfully.');
    }

    public function deleteShipping($id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->delete();

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping deleted successfully.');
    }

    // Manage payments
    public function payments()
    {
        $payments = Payment::all();
        return view('admin.payments.index', compact('payments'));
    }

    public function createPayment()
    {
        return view('admin.payments.create');
    }

    public function storePayment(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        $payment = new Payment($validatedData);
        $payment->save();

        return redirect()->route('admin.payments.index')->with('success', 'Payment record created successfully.');
    }

    public function editPayment($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.payments.edit', compact('payment'));
    }

    public function updatePayment(Request $request, $id)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Payment record updated successfully.');
    }

    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Payment record deleted successfully.');
    }
}
