
namespace Tests\GraphQL;

use Tests\TestCase;

class ShippingCRUDTest extends TestCase
{
public function testShippingCRUDOperations()
{
// Create a new shipping
$createResponse = $this->graphql('
mutation {
createShipping(input: {
order_id: 1,
address: "123 Main St",
city: "Test City",
state: "TS",
postal_code: "12345"
}) {
id
order_id
address
city
state
postal_code
}
}
');

$createResponse->assertJsonStructure([
'data' => [
'createShipping' => [
'id',
'order_id',
'address',
'city',
'state',
'postal_code'
]
]
]);

$shippingId = $createResponse->json('data.createShipping.id');

// Read the created shipping
$readResponse = $this->graphql('
query {
shipping(id: ' . $shippingId . ') {
id
order_id
address
city
state
postal_code
}
}
');

$readResponse->assertJson([
'data' => [
'shipping' => [
'id' => $shippingId,
'order_id' => 1,
'address' => '123 Main St',
'city' => 'Test City',
'state' => 'TS',
'postal_code' => '12345'
]
]
]);

// Update the shipping
$updateResponse = $this->graphql('
mutation {
updateShipping(id: ' . $shippingId . ', input: {
address: "456 Another St",
city: "Updated City",
state: "UC",
postal_code: "67890"
}) {
id
order_id
address
city
state
postal_code
}
}
');

$updateResponse->assertJson([
'data' => [
'updateShipping' => [
'id' => $shippingId,
'order_id' => 1,
'address' => '456 Another St',
'city' => 'Updated City',
'state' => 'UC',
'postal_code' => '67890'
]
]
]);

// Delete the shipping
$deleteResponse = $this->graphql('
mutation {
deleteShipping(id: ' . $shippingId . ') {
id
}
}
');

$deleteResponse->assertJson([
'data' => [
'deleteShipping' => [
'id' => $shippingId
]
]
]);

// Verify the shipping has been deleted
$readAfterDeleteResponse = $this->graphql('
query {
shipping(id: ' . $shippingId . ') {
id
order_id
address
city
state
postal_code
}
}
');

$readAfterDeleteResponse->assertJson([
'data' => [
'shipping' => null
]
]);
}
}
