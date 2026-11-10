<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: Roboto, sans-serif;
        background: #f8fafc;
    }
</style>

<div class="max-w-4xl mx-auto py-6 px-4 sm:py-7">
    <header class="mb-6">
        <h1 class="text-xl sm:text-2xl text-gray-900 mb-1" style="font-family: Roboto;">PASCAL RESOURCES ENERGY, INC.</h1>
        <div class="flex w-full mb-2">
            <div class="w-full border-[3px] border-[#59c2e5]"></div>
            <div class="w-full border-[3px] border-[#fe0001]"></div>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Layout Design Request</h1>
        <p class="text-sm text-gray-500 mt-2">CEO-MBD-FOR-001-000</p>
    </header>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('ldr.public.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                <strong>Please look for the following:</strong>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 mb-5">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center">A</span>
                <h2 class="text-base font-bold text-gray-900">Request Information</h2>
            </div>

            <div style="background-color: #fffbeb; color: #92400e; padding: 10px 14px; border-radius: 8px; margin-bottom: 24px;">
                <small>
                    This form is designed for layout design requests from various departments. For long-term project requests, please allow us a minimum of 3-5 working days to fulfill your requirements prior to the date the materials are needed.
                </small>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="requestor_first_name" required placeholder="Juan" class="field">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="requestor_last_name" required placeholder="Dela Cruz" class="field">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Partners <span class="text-red-500">*</span></label>
                    <select name="department" required class="field">
                        <option value="" selected disabled>Select department</option>
                        <option value="Provincial Distributor (PD)">Provincial Distributor</option>
                        <option value="Area Distributor (AD)">Area Distributor</option>
                        <option value="Mega Dealer">Mega Dealer</option>
                        <option value="Dealer">Dealer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="requestor_email" required placeholder="example@email.com" class="field">
                </div>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 mb-5">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center">B</span>
                <h2 class="text-base font-bold text-gray-900">Design Details</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Medium Type <span class="text-red-500">*</span></label>
                    <select name="medium_type" required class="field">
                        <option value="" selected disabled>Select medium type</option>
                        @foreach (\App\LayoutDesign::MEDIUM_TYPES as $medium)
                            <option value="{{ $medium }}">{{ $medium }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Intended Use <span class="text-red-500">*</span></label>
                    <div class="flex flex-col gap-2">
                        @foreach (\App\LayoutDesign::INTENDED_USES as $use)
                            <label class="choice-card flex items-center gap-3 border border-gray-200 rounded-lg p-2 cursor-pointer hover:border-blue-400">
                                <input type="checkbox" name="intended_use[]" value="{{ $use }}" class="accent-blue-600">
                                <span class="text-sm font-medium text-gray-900">{{ $use }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-500 mb-2">Marketing Collateral Type <span class="text-red-500">*</span></label>
                <div class="flex flex-col gap-2">
                    @foreach (\App\LayoutDesign::COLLATERAL_TYPES as $collateral)
                        <label class="choice-card flex items-center gap-3 border border-gray-200 rounded-lg p-2 cursor-pointer hover:border-blue-400">
                            <input type="checkbox" name="marketing_collateral_type[]" value="{{ $collateral }}" class="accent-blue-600">
                            <span class="text-sm font-medium text-gray-900">{{ $collateral }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Purpose of the Layout Request (Indicate where it will be used) <span class="text-red-500">*</span></label>
                    <textarea name="purpose_of_layout_request" rows="3" required placeholder="Enter purpose" class="field resize-none"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Project Description (E.g. text to include, size, quantity, etc.) <span class="text-red-500">*</span></label>
                    <textarea name="project_description" rows="4" required placeholder="Enter project description" class="field resize-none"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">
                        Peg Sample (E.g. theme, design, ideas)
                        <span class="text-red-500"><small>*</small><span>
                    </label>
                    <input type="file" name="peg_sample" accept="image/*,.pdf,.doc,.docx,.ppt,.pptx" class="field">
                    <small class="text-gray-400">Max file size: 10MB. Accepted: images, PDF, Word, PowerPoint.</small>

                    <div id="peg_preview" class="mt-2 hidden">
                        <div id="peg_image_preview" class="mb-2"></div>
                        <a id="peg_file_link" href="#" target="_blank" rel="noopener noreferrer" class="text-blue-600 text-sm font-semibold">
                            <span id="peg_file_name"></span>
                        </a>
                        <small class="text-gray-400 block mt-1">Click to change the file attached.</small>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Date &amp; Time Needed <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="date_needed" min="{{ date('Y-m-d\TH:i') }}" required class="field">
                    <small class="text-gray-400">Pick the date and time the materials are needed.</small>
                </div>
            </div>
        </section>

        <div class="flex w-full justify-end">
            <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg">Submit Request</button>
        </div>
    </form>
</div>

<style>
    .field {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: .5rem;
        padding: .625rem .75rem;
        font-size: .875rem;
        color: #374151;
        background: white;
    }

    .field:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px #bfdbfe;
    }

    .label {
        display: block;
        font-size: .875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .5rem;
    }

    .required {
        color: #ef4444;
    }
</style>

<script>
    document.getElementById('peg_sample').addEventListener('change', function() {
        var preview = document.getElementById('peg_preview');
        var fileInput = this;

        document.getElementById('peg_image_preview').innerHTML = '';

        if (fileInput.files && fileInput.files.length) {
            var file = fileInput.files[0];
            var url = URL.createObjectURL(file);

            document.getElementById('peg_file_name').textContent = file.name;
            document.getElementById('peg_file_link').href = url;

            if (file.type.startsWith('image/')) {
                document.getElementById('peg_image_preview').innerHTML =
                    '<img src="' + url + '" alt="Peg sample preview" style="max-height: 200px; border-radius: 8px; border: 1px solid #d1d5db;">';
            }

            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });

    document.querySelectorAll('.choice-card input[type="checkbox"]').forEach(function(input) {
        input.addEventListener('change', function() {
            var card = this.closest('.choice-card');
            if (this.checked) {
                card.classList.remove('border-gray-200');
                card.classList.add('border-blue-600', 'bg-blue-50');
            } else {
                card.classList.remove('border-blue-600', 'bg-blue-50');
                card.classList.add('border-gray-200');
            }
        });
    });
</script>
@include('sweetalert::alert');
