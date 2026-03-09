<h1>Add Food Menu</h1>

<form action="/admin/menu/store" method="POST">

@csrf

<input type="text" name="name" placeholder="Food name">

<input type="text" name="description" placeholder="Description">

<input type="number" name="price" placeholder="Price">

<button type="submit">Save</button>

</form>