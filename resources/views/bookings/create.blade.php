<x-app-layout>
    <div class="container-fluid">
    <div class="bg-gray-50 min-h-screen py-12" x-data="bookingForm({{ $tour->id }}, {{ $tour->prices?->first()->amount ?? 0 }}, {{ $tour->prices?->first()->id ?? 'null' }})">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 -z-10"></div>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-uptrek-red transition-all duration-300 -z-10" :style="'w-' + ((step - 1) * 50) + '%'"></div>
                    
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center cursor-pointer" @click="step > 1 ? step = 1 : null">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300" 
                             :class="step >= 1 ? 'bg-uptrek-red text-white' : 'bg-gray-200 text-gray-500'">
                            1
                        </div>
                        <span class="mt-2 text-xs font-medium" :class="step >= 1 ? 'text-uptrek-red' : 'text-gray-500'">Dates & Guests</span>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300"
                             :class="step >= 2 ? 'bg-uptrek-red text-white' : 'bg-gray-200 text-gray-500'">
                            2
                        </div>
                        <span class="mt-2 text-xs font-medium" :class="step >= 2 ? 'text-uptrek-red' : 'text-gray-500'">Personal Details</span>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center">
                         <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300"
                             :class="step >= 3 ? 'bg-uptrek-red text-white' : 'bg-gray-200 text-gray-500'">
                            3
                        </div>
                        <span class="mt-2 text-xs font-medium" :class="step >= 3 ? 'text-uptrek-red' : 'text-gray-500'">Confirm</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100">
                <!-- Header -->
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white">Book Your Spot</h1>
                        <p class="text-sm text-gray-400">{{ $tour->title }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Total Price</p>
                        <p class="text-2xl font-bold text-white">$<span x-text="calculateTotal()"></span></p>
                    </div>
                </div>

                <div class="p-8">
                    
                    <!-- Step 1: Dates & Guests -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Select Dates & Guests</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                <input type="date" x-model="form.start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-uptrek-red focus:ring focus:ring-uptrek-red focus:ring-opacity-50">
                                <!-- Ideally, populate with tour available dates -->
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                                <div class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-gray-500">
                                    {{ $tour->duration_days }} Days
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Guests</label>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">Adults</span>
                                    <span class="text-xs text-gray-500">Age 13+</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button @click="if(form.adults_count > 1) form.adults_count--" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">-</button>
                                    <span x-text="form.adults_count" class="font-medium w-4 text-center"></span>
                                    <button @click="form.adults_count++" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">+</button>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">Children</span>
                                    <span class="text-xs text-gray-500">Age 2-12</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button @click="if(form.children_count > 0) form.children_count--" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">-</button>
                                    <span x-text="form.children_count" class="font-medium w-4 text-center"></span>
                                    <button @click="form.children_count++" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button @click="step = 2" class="bg-uptrek-red text-white px-6 py-2 rounded-md font-bold hover:bg-red-700 transition-colors">
                                Next Step
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Personal Details -->
                    <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Your Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" x-model="form.first_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-uptrek-red focus:ring focus:ring-uptrek-red focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" x-model="form.last_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-uptrek-red focus:ring focus:ring-uptrek-red focus:ring-opacity-50">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" x-model="form.email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-uptrek-red focus:ring focus:ring-uptrek-red focus:ring-opacity-50">
                            </div>
                             <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" x-model="form.phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-uptrek-red focus:ring focus:ring-uptrek-red focus:ring-opacity-50">
                            </div>
                             <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <input type="text" x-model="form.country" class="w-full rounded-md border-gray-300 shadow-sm focus:border-uptrek-red focus:ring focus:ring-uptrek-red focus:ring-opacity-50" placeholder="e.g. United States">
                            </div>
                             <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests (Optional)</label>
                                <textarea x-model="form.special_requests" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-uptrek-red focus:ring focus:ring-uptrek-red focus:ring-opacity-50"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button @click="step = 1" class="text-gray-600 font-medium hover:text-gray-900">
                                Back
                            </button>
                            <button @click="step = 3" class="bg-uptrek-red text-white px-6 py-2 rounded-md font-bold hover:bg-red-700 transition-colors">
                                Review booking
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Confirmation -->
                    <div x-show="step === 3" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Review & Confirm</h2>
                        
                        <div class="bg-gray-50 rounded-md p-6 mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Retreat</span>
                                <span class="font-medium text-gray-900">{{ $tour->title }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Start Date</span>
                                <span class="font-medium text-gray-900" x-text="form.start_date"></span>
                            </div>
                             <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Guests</span>
                                <span class="font-medium text-gray-900">
                                    <span x-text="form.adults_count"></span> Adults, 
                                    <span x-text="form.children_count"></span> Children
                                </span>
                            </div>
                            <div class="border-t border-gray-200 my-4 pt-4 flex justify-between">
                                <span class="text-gray-900 font-bold">Total Price</span>
                                <span class="text-uptrek-red font-bold text-lg">$<span x-text="calculateTotal()"></span></span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-start">
                                <input type="checkbox" class="mt-1 rounded border-gray-300 text-uptrek-red focus:ring-uptrek-red h-4 w-4">
                                <span class="ml-3 text-sm text-gray-600">I agree to the <a href="#" class="text-uptrek-red hover:underline">Terms & Conditions</a> and <a href="#" class="text-uptrek-red hover:underline">Privacy Policy</a>.</span>
                            </label>
                        </div>

                        <div class="mt-8 flex justify-between items-center">
                            <button @click="step = 2" class="text-gray-600 font-medium hover:text-gray-900">
                                Back
                            </button>
                            <button @click="submitBooking()" class="bg-uptrek-red text-white px-8 py-3 rounded-md font-bold hover:bg-red-700 transition-colors shadow-lg" :disabled="loading" :class="{'opacity-50 cursor-not-allowed': loading}">
                                <span x-show="!loading">Confirm Booking</span>
                                <span x-show="loading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Success Message -->
                     <div x-show="step === 4" style="display: none;" class="text-center py-12">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Booking Confirmed!</h2>
                        <p class="text-gray-600 mb-8">Your booking reference is <span class="font-mono font-bold text-gray-900" x-text="bookingReference"></span>.</p>
                        <a href="{{ route('home') }}" class="inline-block bg-gray-900 text-white px-6 py-2 rounded-md font-medium hover:bg-black transition-colors">Return Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
             Alpine.data('bookingForm', (tourId, basePrice, initialPriceId) => ({
                step: 1,
                loading: false,
                bookingReference: '',
                form: {
                    tour_package_id: tourId,
                    start_date: new Date().toISOString().split('T')[0],
                    adults_count: 2,
                    children_count: 0,
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    country: '', // Added country
                    special_requests: ''
                },
                basePrice: basePrice,
                priceId: initialPriceId,

                calculateTotal() {
                    return (this.form.adults_count * this.basePrice) + (this.form.children_count * this.basePrice * 0.5); 
                },

                async submitBooking() {
                    if (!this.priceId) {
                        alert('Price calculation error (No price ID). Please contact support.');
                        return;
                    }

                    this.loading = true;
                    
                    const payload = {
                        bookable_type: 'App\\Models\\Tour\\TourPackage',
                        bookable_id: this.form.tour_package_id,
                        price_id: this.priceId,
                        booking_date: this.form.start_date, // Mapped to booking_date per migration
                        customer_name: `${this.form.first_name} ${this.form.last_name}`.trim(),
                        customer_email: this.form.email,
                        customer_phone: this.form.phone,
                        customer_country: this.form.country,
                        adults_count: this.form.adults_count,
                        children_count: this.form.children_count,
                        // end_date removed as it's not in the booking table/request
                    };

                    try {
                        const response = await fetch('/bookings', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.bookingReference = data.booking.booking_reference;
                            this.step = 4;
                        } else {
                            // Format validation errors if available
                            let msg = data.message || 'Unknown error';
                            if (data.errors) {
                                msg += '\n' + Object.values(data.errors).flat().join('\n');
                            }
                            alert('Booking failed: ' + msg);
                            console.error(data);
                        }
                    } catch (error) {
                        alert('An error occurred. Please try again.');
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
