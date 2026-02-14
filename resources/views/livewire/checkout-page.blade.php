<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
	<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
		Checkout
	</h1>
	<form wire:submit.prevent="placeOrder">
        <div class="grid grid-cols-12 gap-4">
            <div class="md:col-span-12 lg:col-span-8 col-span-12">
                <!-- Card -->
                <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                    <!-- Shipping Address -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
                            Shipping Address
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label  class="block text-gray-700 dark:text-white mb-1" for="first_name">
                                    First Name
                                </label>
                                <input wire:model="first_name" class="w-full rounded-lg border py-2 px-3 @error('first_name') border-red-500 @enderror dark:bg-gray-700 dark:text-white dark:border-none" id="first_name" type="text">
                                </input>

                                @error('first_name')
                                    <span class="text-red-500 text-sm"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-white mb-1" for="last_name">
                                    Last Name
                                </label>
                                <input wire:model="last_name" class="w-full  @error('last_name') border-red-500 @enderror rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="last_name" type="text">
                                </input>
                                <div>
                                    @error('last_name')
                                        <span class="text-red-500 text-sm"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-gray-700 dark:text-white mb-1" for="phone">
                                Phone
                            </label>
                            <input wire:model="phone"  class="w-full @error('phone') border-red-500 @enderror rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="phone" type="text">
                            </input>
                            <div>
                                @error('phone')
                                    <span class="text-red-500 text-sm"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-gray-700 dark:text-white mb-1" for="address">
                                Address
                            </label>
                            <input wire:model="street_address" class="w-full rounded-lg @error('street_address') border-red-500 @enderror border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="address" type="text">
                            </input>
                            <div>
                                @error('street_address')
                                    <span class="text-red-500 text-sm"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-gray-700 dark:text-white mb-1" for="city">
                                City
                            </label>
                            <input wire:model="city" class="w-full @error('city') border-red-500 @enderror rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="city" type="text">
                            </input>
                            <div>
                                @error('city')
                                    <span class="text-red-500 text-sm"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-gray-700 dark:text-white mb-1" for="state">
                                    State
                                </label>
                                <input wire:model="state" class="w-full @error('state') border-red-500 @enderror rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="state" type="text">
                                </input>
                                <div>
                                    @error('state')
                                        <span class="text-red-500 text-sm"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-white mb-1" for="zip">
                                    ZIP Code
                                </label>
                                <input wire:model="zip_code" class="w-full @error('zip_code') border-red-500 @enderror rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="zip" type="text">
                                </input>
                                <div>
                                    @error('zip_code')
                                        <span class="text-red-500 text-sm"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold mb-4 dark:text-white">
                        Select Payment Method
                    </div>
                    <ul class="grid w-full gap-6 md:grid-cols-2">
                        <li>
                            <input class="hidden peer" id="hosting-small" wire:model="payment_method" required="" type="radio" value="cod" />
                            <label class="inline-flex items-center justify-between w-full p-5 text-gray-500  bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 @error('payment_method') border-red-500 @enderror hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="hosting-small">
                                <div class="block">
                                    <div class="w-full text-lg font-semibold">
                                        Cash on Delivery
                                    </div>
                                </div>
                                <svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    </path>
                                </svg>
                            </label>
                        </li>
                        @foreach ($payment_methods as $method)
                            <li>
                                <input class="hidden peer" id="hosting-{{$method->id}}" wire:model="payment_method" value="{{$method->name}}" required="" type="radio" />
                                <label class="inline-flex items-center justify-between w-full p-5 text-gray-500  bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 @error('payment_method') border-red-500 @enderror hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="hosting-{{$method->id}}">
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">
                                            {{ $method->name }}
                                        </div>
                                    </div>
                                    <svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        </path>
                                    </svg>
                                </label>
                            </li>
                        @endforeach
                        <li>
                            <input class="hidden peer" id="hosting-big" wire:model="payment_method" type="radio" value="stripe">
                            <label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 @error('payment_method') border-red-500 @enderror hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="hosting-big">
                                <div class="block">
                                    <div class="w-full text-lg font-semibold">
                                        Stripe
                                    </div>
                                </div>
                                <svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    </path>
                                </svg>
                            </label>
                            </input>
                        </li>
                    </ul>
                    <div>
                        @error('payment_method')
                            <span class="text-red-500 text-sm"> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="md:col-span-12 lg:col-span-4 col-span-12">
                <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                    <div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
                        ORDER SUMMARY
                    </div>
                    <div class="flex justify-between mb-2 font-bold">
                        <span class="dark:text-white">
                            Subtotal
                        </span>
                        <span class="dark:text-white">
                            {{ Number::currency($grandTotal)}}
                        </span>
                    </div>
                    <div class="flex justify-between mb-2 font-bold">
                        <span class="dark:text-white">
                            Taxes
                        </span>
                        <span class="dark:text-white">
                            {{ Number::currency(0)}}
                        </span>
                    </div>
                    <div class="flex justify-between mb-2 font-bold">
                        <span class="dark:text-white">
                            Shipping Cost
                        </span>
                        <span class="dark:text-white">
                            {{ Number::currency($grandTotal)}}
                        </span>
                    </div>
                    <hr class="bg-slate-400 my-4 h-1 rounded">
                    <div class="flex justify-between mb-2 font-bold">
                        <span class="dark:text-white">
                            Grand Total
                        </span>
                        <span class="dark:text-white">
                            {{ Number::currency($grandTotal)}}
                        </span>
                    </div>
                    </hr>
                </div>
                <button type="submit" class="bg-green-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-green-600">
                    <span wire:loading.remove wire:target="placeOrder">Place Order</span>
                    <span wire:loading wire:target="placeOrder">Processing...</span>
                </button>
                <div class="bg-white mt-4 rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                    <div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
                        BASKET SUMMARY
                    </div>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
                        @foreach ($cartItems as $item)
                            <li class="py-3 sm:py-4" wire:key="{{$item['product_id']}}">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img alt="Neil image" class="w-12 h-12 rounded-full" src="{{url('storage',$item['image'])}}">
                                        </img>
                                    </div>
                                    <div class="flex-1 min-w-0 ms-4">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{$item['name']}}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            Quantity: {{$item['quantity']}}
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        {{Number::currency($item['total_amount'])}}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        {{-- <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img alt="Neil image" class="w-12 h-12 rounded-full" src="https://iplanet.one/cdn/shop/files/iPhone_15_Pro_Max_Blue_Titanium_PDP_Image_Position-1__en-IN_1445x.jpg?v=1695435917">
                                    </img>
                                </div>
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        Apple iPhone 15 Pro Max
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        Quantity: 1
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    $320
                                </div>
                            </div>
                        </li> --}}

                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
