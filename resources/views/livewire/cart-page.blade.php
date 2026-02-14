    <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl dark:text-white font-semibold mb-4">Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
        <div class="md:w-3/4">
            <div class="bg-white dark:bg-gray-600 overflow-x-auto rounded-lg shadow-md p-6 mb-4  hover:-mt-4 hover:mb-4 cursor-pointer transition-all duration-300 ease-in-out">
            <table class="w-full ">
                <thead >
                <tr>
                    <th class="text-left font-semibold dark:text-white">Product</th>
                    <th class="text-left font-semibold dark:text-white">Price</th>
                    <th class="text-left font-semibold dark:text-white">Quantity</th>
                    <th class="text-left font-semibold dark:text-white">Total</th>
                    <th class="text-left font-semibold dark:text-white">Remove</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($cartItems as $item)
                        <tr class="">
                            <td class="py-4">
                                <div class="flex items-center">
                                    <img class="h-16 w-16 mr-4" src="{{url('storage' , $item['image'])}}" alt="Product image">
                                    <span class="font-semibold dark:text-white"> {{ $item['name']}} </span>
                                </div>
                            </td>
                            <td class="py-4 dark:text-white">{{ Number::currency($item['unit_amount'])}}</td>
                            <td class="py-4">
                            <div class="flex items-center">
                                <button wire:click="decreaceQty({{ $item['product_id'] }})" class="dark:text-white border rounded-md py-2 px-4 mr-2 hover:bg-red-600">-</button>
                                <span class="text-center w-8 dark:text-white">{{ $item['quantity']}}</span>
                                <button wire:click="increaceQty({{ $item['product_id'] }})" class=" dark:text-white border rounded-md py-2 px-4 ml-2 hover:bg-red-600">+</button>
                            </div>
                            </td>
                            <td class="py-4 dark:text-white">{{ Number::currency($item['total_amount'])}}</td>
                            <td><button wire:click="removeItem({{$item['product_id']}})" class="bg-slate-300 border-2  border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700"><span wire:loading.remove wire:target="removeItem({{$item['product_id']}})">Remove</span><span wire:loading wire:target="removeItem({{$item['product_id']}})">removing</span></button></td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-4xl font-semibold dark:text-white text-slate-500">
                            No items available in cart!
                        </td>
                    </tr>
                    @endforelse
                {{-- <tr>
                    <td class="py-4">
                    <div class="flex items-center">
                        <img class="h-16 w-16 mr-4" src="https://placehold.co/150" alt="Product image">
                        <span class="font-semibold">Product name</span>
                    </div>
                    </td>
                    <td class="py-4">$19.99</td>
                    <td class="py-4">
                    <div class="flex items-center">
                        <button class="border rounded-md py-2 px-4 mr-2">-</button>
                        <span class="text-center w-8">1</span>
                        <button class="border rounded-md py-2 px-4 ml-2">+</button>
                    </div>
                    </td>
                    <td class="py-4">$19.99</td>
                    <td><button class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700">Remove</button></td>
                </tr> --}}
                <!-- More product rows -->
                </tbody>
            </table>
            </div>
        </div>
        <div class="md:w-1/4">
            <div class="bg-white dark:bg-gray-600 rounded-lg shadow-md p-6 hover:-mt-4 hover:mb-4 cursor-pointer transition-all duration-300 ease-in-out">
            <h2 class="text-lg font-semibold mb-4 dark:text-white">Summary</h2>
            <div class="flex justify-between mb-2">
                <span class="dark:text-white">Subtotal</span>
                <span class="dark:text-white">{{Number::currency($grandTotal)}}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="dark:text-white">Taxes</span>
                <span class="dark:text-white">{{Number::currency(0)}}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="dark:text-white">Shipping</span>
                <span class="dark:text-white">{{Number::currency(0)}}</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between mb-2">
                <span class="font-semibold dark:text-white">Grand Total</span>
                <span class="font-semibold dark:text-white">{{ Number::currency($grandTotal)}}</span>
            </div>
            @if ($cartItems)
            <a wire:navigate href="checkout">
                <button class="bg-blue-500 text-white py-2 px-4 rounded-lg mt-4 w-full">Checkout</button>
            </a>

            @endif

            </div>
        </div>
        </div>
    </div>
    </div>
