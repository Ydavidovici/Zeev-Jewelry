<div class="form-group">
    <label for="order_id">Order ID</label>
    <input type="text" name="order_id" class="form-control" value="{{ old('order_id', $shipping->order_id ?? '') }}">
</div>
<div class="form-group">
    <label for="address">Address</label>
    <input type="text" name="address" class="form-control" value="{{ old('address', $shipping->address ?? '') }}">
</div>
<div class="form-group">
    <label for="city">City</label>
    <input type="text" name="city" class="form-control" value="{{ old('city', $shipping->city ?? '') }}">
</div>
<div class="form-group">
    <label for="state">State</label>
    <input type="text" name="state" class="form-control" value="{{ old('state', $shipping->state ?? '') }}">
</div>
<div class="form-group">
    <label for="postal_code">Postal Code</label>
    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $shipping->postal_code ?? '') }}">
</div>
<div class="form-group">
    <label for="country">Country</label>
    <input type="text" name="country" class="form-control" value="{{ old('country', $shipping->country ?? '') }}">
</div>
<div class="form-group">
    <label for="shipping_method">Shipping Method</label>
    <input type="text" name="shipping_method" class="form-control" value="{{ old('shipping_method', $shipping->shipping_method ?? '') }}">
</div>
<div class="form-group">
    <label for="tracking_number">Tracking Number</label>
    <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number', $shipping->tracking_number ?? '') }}">
</div>
<div class="form-group">
    <label for="status">Status</label>
    <input type="text" name="status" class="form-control" value="{{ old('status', $shipping->status ?? '') }}">
</div>
