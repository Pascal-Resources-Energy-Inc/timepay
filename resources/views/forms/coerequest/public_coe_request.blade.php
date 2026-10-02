<meta name="viewport" content="width=device-width, initial-scale-1"></meta>
<script src="https://cdn.tailwindcss.com"></script>
<script src="{{ asset('/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/vendor/sweetalert2/sweetalert2.min.css') }}">

<!-- roboto font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<div class="max-w-2xl mx-auto py-6 px-4">

    @include('sweetalert::alert')

    {{-- Company Header --}}
    <h1 class="text-xl sm:text-2xl text-gray-900 mb-1" style="font-family: Roboto;">PASCAL RESOURCES ENERGY, INC.</h1>
    <div class="flex w-full mb-2">
        <div class="w-full border-[3px] border-[#59c2e5]"></div>
        <div class="w-full border-[3px] border-[#fe0001]"></div>
    </div>
    <h1 class="text-lg sm:text-2xl font-bold text-gray-900 mb-1">Certificate of Employment (COE) Request</h1>
    <p class="text-sm text-gray-500 mb-6">Please provide the details below to request your Certificate of Employment.</p>

    <form id="publicCoeRequestForm" method="POST" action="/public/coe-request">
        @csrf

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <strong>{{ $errors->first() }}</strong>
        </div>
        @endif

        {{-- Section 1: Request Details --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 mb-5">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center flex-shrink-0">1</span>
                <h2 class="text-base font-bold text-gray-900">Request Details</h2>
            </div>

            {{-- Certificate Type --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Certificate Type <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="cert-type-card flex items-start gap-3 border-2 border-blue-600 bg-blue-50 rounded-xl p-4 cursor-pointer">
                        <input type="radio" name="reason_for_request" value="Plain" class="mt-0.5 accent-blue-600" checked>
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Certificate of Employment (COE)</div>
                            <div class="text-xs text-gray-500 mt-0.5">Plain COE without salary details</div>
                        </div>
                    </label>
                    <label class="cert-type-card flex items-start gap-3 border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-400">
                        <input type="radio" name="reason_for_request" value="With Salary" class="mt-0.5 accent-blue-600">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Certificate of Employment with Salary Details</div>
                            <div class="text-xs text-gray-500 mt-0.5">COE including salary details</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Purpose of Request --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Purpose of Request <span class="text-red-500">*</span>
                </label>
                <select name="purpose" id="purpose_select"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="" selected disabled hidden>Select purpose</option>
                    <option value="Employment">Employment</option>
                    <option value="Other" class="text-gray-500">Other...</option>
                </select>
            </div>

            {{-- If other, specify --}}
            <div id="other-purpose-wrapper" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">If other, please specify</label>
                <input type="text"
                    name="purpose_other"
                    id="purpose_other"
                    placeholder="Enter purpose"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div id="visa-purpose-wrapper" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Country of Destination</label>
                <input type="text"
                    name="purpose_visa"
                    id="purpose_visa"
                    placeholder="Enter country of destination (e.g. Australia, Japan, UAE)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div id="bank-purpose-wrapper" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Specify Loan Type</label>
                <input type="text"
                    name="purpose_bank"
                    id="purpose_bank"
                    placeholder="e.g. Car Loan, Housing Loan, Personal Loan"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        {{-- Section 2: Employee Information --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 mb-5">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center flex-shrink-0">2</span>
                <h2 class="text-base font-bold text-gray-900">Employee Information</h2>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="grid grid-cols-1">
                    <div class="p-4 border-b border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2">
                            <div class="">
                                <div class="text-xs text-gray-500 mb-1">First Name <span class="text-red-500">*</span></div>
                                <input
                                        type="text"
                                        name="first_name"
                                        required
                                        placeholder="Juan"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="ml-0 sm:ml-5 mt-2 sm:mt-0">
                                <div class="text-xs text-gray-500 mb-1">Last Name <span class="text-red-500">*</span></div>
                                <input
                                        type="text"
                                        name="last_name"
                                        required
                                        placeholder="Dela Cruz"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-1">Hiring Date <span class="text-red-500">*</span></div>
                            <input type="date"
                                name="hire_date"
                                required
                                max="{{ date('Y-m-d') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-1">Resignation Date <span class="text-red-500">*</span></div>
                            <input type="date"
                                name="resign_date"
                                required
                                max="{{ date('Y-m-d') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="p-4 border-b border-gray-200">
                        <div class="text-xs text-gray-500 mb-1">Position <span class="text-red-500">*</span></div>
                        <input
                                type="text"
                                name="designation"
                                required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-1">
                        <div class="p-4 border-t border-gray-200">
                            <div class="text-xs text-gray-500 mb-1">Gender <span class="text-red-500">*</span></div>
                            <select name="gender" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="p-4 border-b border-gray-200"> -->
                    <!--     <div class="text-xs text-gray-500 mb-1">Employment Status <span class="text-red-500">*</span></div> -->
                    <!--     <select name="employment_status" required -->
                    <!--         class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"> -->
                    <!--         <option value="" disabled>Select status</option> -->
                    <!--         <option value="Separated" selected>Separated - Former Employee</option> -->
                    <!--     </select> -->
                    <!-- </div> -->
                </div>
            </div>
        </div>

        {{-- Section 3: Delivery Method --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 mb-5">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center flex-shrink-0">3</span>
                <h2 class="text-base font-bold text-gray-900">Delivery Method</h2>
            </div>

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                I would like to receive my COE thru <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <label class="delivery-card flex items-center gap-3 border-2 border-blue-600 bg-blue-50 rounded-xl px-4 py-3 cursor-pointer">
                    <input type="radio" name="receive_method" value="Email" class="accent-blue-600" checked>
                    <i class="bi bi-envelope text-gray-500 text-base"></i>
                    <span class="text-sm font-medium text-gray-800">Email</span>
                </label>
                <label class="delivery-card flex items-center gap-3 border-2 border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400">
                    <input type="radio" name="receive_method" value="Viber" class="accent-blue-600">
                    <i class="bi bi-phone text-gray-500 text-base"></i>
                    <span class="text-sm font-medium text-gray-800">Viber</span>
                </label>
                <label class="delivery-card flex items-center gap-3 border-2 border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400">
                    <input type="radio" name="receive_method" value="Hard Copy" class="accent-blue-600">
                    <i class="bi bi-building text-gray-500 text-base"></i>
                    <span class="text-sm font-medium text-gray-800">HR Office (Hard Copy)</span>
                </label>
            </div>

            {{-- Email field --}}
            <div id="email-field-wrapper">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                    <span id="email-hint" class="hidden text-xs font-normal text-gray-500 ml-1">
                        (Notifications about your COE request will be sent here.)
                    </span>
                </label>
                <input type="email"
                    name="email"
                    id="coe_email"
                    placeholder="email@example.com"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Viber field --}}
            <div id="viber-field-wrapper" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Viber Number <span class="text-red-500">*</span>
                </label>
                <input type="tel"
                    name="viber_number"
                    id="coe_viber"
                    placeholder="09XXXXXXXXX"
                    maxlength="11"
                    pattern="[0-9]{11}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1.5">COE will be sent to this Viber number.</p>
            </div>

            {{-- Hard Copy message --}}
            <div id="hardcopy-wrapper" class="hidden">
                <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg mt-2 px-4 py-3 text-sm">
                    <i class="bi bi-building me-1"></i>
                    Your COE will be available for pickup at the HR office.
                </div>
            </div>
        </div>

        {{-- Section 4: Additional Instructions --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center flex-shrink-0">4</span>
                <h2 class="text-base font-bold text-gray-900">
                    Additional Notes, if any
                    <span class="text-sm font-normal text-gray-500 ml-1">(Optional)</span>
                </h2>
            </div>

            <textarea name="additional_notes"
                id="additional_notes"
                rows="3"
                maxlength="500"
                placeholder="Enter any additional instructions or notes (optional)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            <p class="text-xs text-gray-500 mt-1.5">Maximum 500 characters</p>
        </div>

        {{-- Footer Actions --}}
        <div class="flex justify-end">
            <button type="submit"
                class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition text-center">
                Submit
            </button>
        </div>

    </form>
</div>

<script>
    document.querySelectorAll('input[name="reason_for_request"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.cert-type-card').forEach(function(card) {
                card.classList.remove('border-blue-600', 'bg-blue-50');
                card.classList.add('border-gray-200');
            });
            radio.closest('.cert-type-card').classList.add('border-blue-600', 'bg-blue-50');
            radio.closest('.cert-type-card').classList.remove('border-gray-200');
        });
    });

    document.querySelectorAll('input[name="receive_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.delivery-card').forEach(function(card) {
                card.classList.remove('border-blue-600', 'bg-blue-50');
                card.classList.add('border-gray-200');
            });
            radio.closest('.delivery-card').classList.add('border-blue-600', 'bg-blue-50');
            radio.closest('.delivery-card').classList.remove('border-gray-200');

            var val = radio.value;
            document.getElementById('email-field-wrapper').classList.toggle('hidden', val === 'Viber');
            document.getElementById('viber-field-wrapper').classList.toggle('hidden', val !== 'Viber');
            document.getElementById('hardcopy-wrapper').classList.toggle('hidden', val !== 'Hard Copy');
            document.getElementById('coe_email').required = val === 'Email' || val === 'Hard Copy';
            document.getElementById('email-hint').classList.toggle('hidden', val !== 'Hard Copy');
            document.getElementById('coe_viber').required = val === 'Viber';
        });
    });

    document.getElementById('purpose_select').addEventListener('change', function() {
        var val = this.value;
        document.getElementById('other-purpose-wrapper').classList.toggle('hidden', val !== 'Other');
        document.getElementById('visa-purpose-wrapper').classList.toggle('hidden', val !== 'Visa Application');
        document.getElementById('bank-purpose-wrapper').classList.toggle('hidden', val !== 'Bank');
    });
</script>
