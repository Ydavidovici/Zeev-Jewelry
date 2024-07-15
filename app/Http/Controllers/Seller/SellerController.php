<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Shipping;
use App\Models\Payment;

class SellerController extends Controller
{
    // Show the seller dashboard
    public function index()
    {
        $products = Product::where('seller_id', auth()->id())->get();
        $orders = Order::where('seller_id', auth()->id())->get();
        return view('seller.dashboard', compact('products', 'orders'));
    }

    // Manage products
    public function products()
    {
        $products = Product::where('seller_id', auth()->id())->get();
        return view('seller.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('seller.products.create');
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
        $product->seller_id = auth()->id();
        $product->save();

        // Add to inventory as well
        Inventory::create([
            'product_id' => $product->id,
            'quantity' => $validatedData['stock_quantity'],
            'seller_id' => auth()->id(),
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully.');
    }

    public function editProduct($id)
    {
        $product = Product::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        return view('seller.products.edit', compact('product'));
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

        $product = Product::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $product->update($validatedData);

        // Update inventory as well
        $inventory = Inventory::where('product_id', $product->id)->where('seller_id', auth()->id())->first();
        if ($inventory) {
            $inventory->update(['quantity' => $validatedData['stock_quantity']]);
        } else {
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => $validatedData['stock_quantity'],
                'seller_id' => auth()->id(),
            ]);
        }

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $product->delete();

        // Delete from inventory as well
        Inventory::where('product_id', $product->id)->where('seller_id', auth()->id())->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully.');
    }

    // Manage orders
    public function orders()
    {
        $orders = Order::where('seller_id', auth()->id())->get();
        return view('seller.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Order::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        return view('seller.orders.show', compact('order'));
    }

    // Manage inventory
    public function inventory()
    {
        $inventory = Inventory::where('seller_id', auth()->id())->get();
        return view('seller.inventory.index', compact('inventory'));
    }

    public function addToInventory()
    {
        $products = Product::where('seller_id', auth()->id())->get();
        return view('seller.inventory.create', compact('products'));
    }

    public function storeInventory(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::updateOrCreate(
            ['product_id' => $validatedData['product_id'], 'seller_id' => auth()->id()],
            ['quantity' => $validatedData['quantity'], 'location' => $validatedData['location']]
        );

        return redirect()->route('seller.inventory.index')->with('success', 'Inventory added/updated successfully.');
    }

    public function editInventory($id)
    {
        $inventory = Inventory::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $products = Product::where('seller_id', auth()->id())->get();
        return view('seller.inventory.edit', compact('inventory', 'products'));
    }

    public function updateInventory(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'location' => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $inventory->update($validatedData);

        return redirect()->route('seller.inventory.index')->with('success', 'Inventory updated successfully.');
    }

    public function deleteInventory($id)
    {
        $inventory = Inventory::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $inventory->delete();

        return redirect()->route('seller.inventory.index')->with('success', 'Inventory deleted successfully.');
    }

    // Manage shipping
    public function shipping()
    {
        $shipping = Shipping::where('seller_id', auth()->id())->get();
        return view('seller.shipping.index', compact('shipping'));
    }

    public function createShipping()
    {
        return view('seller.shipping.create');
    }

    public function storeShipping(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $shipping = new Shipping($validatedData);
        $shipping->seller_id = auth()->id();
        $shipping->save();

        return redirect()->route('seller.shipping.index')->with('success', 'Shipping created successfully.');
    }

    public function editShipping($id)
    {
        $shipping = Shipping::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        return view('seller.shipping.edit', compact('shipping'));
    }

    public function updateShipping(Request $request, $id)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $shipping = Shipping::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $shipping->update($validatedData);

        return redirect()->route('seller.shipping.index')->with('success', 'Shipping updated successfully.');
    }

    public function deleteShipping($id)
    {
        $shipping = Shipping::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $shipping->delete();

        return redirect()->route('seller.shipping.index')->with('success', 'Shipping deleted successfully.');
    }

    // Manage payments
    public function payments()
    {
        $payments = Payment::where('seller_id', auth()->id())->get();
        return view('seller.payments.index', compact('payments'));
    }

    public function createPayment()
    {
        return view('seller.payments.create');
    }

    public function storePayment(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        $payment = new Payment($validatedData);
        $payment->seller_id = auth()->id();
        $payment->save();

        return redirect()->route('seller.payments.index')->with('success', 'Payment record created successfully.');
    }

    public function editPayment($id)
    {
        $payment = Payment::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        return view('seller.payments.edit', compact('payment'));
    }

    public function updatePayment(Request $request, $id)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        $payment = Payment::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $payment->update($validatedData);

        return redirect()->route('seller.payments.index')->with('success', 'Payment record updated successfully.');
    }

    public function deletePayment($id)
    {
        $payment = Payment::where('id', $id)->where('seller_id', auth()->id())->firstOrFail();
        $payment->delete();

        return redirect()->route('seller.payments.index')->with('success', 'Payment record deleted successfully.');
    }
}
