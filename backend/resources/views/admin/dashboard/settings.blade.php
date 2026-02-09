@extends('layouts.admin')

@section('title', 'Settings - BixCash Admin')
@section('page-title', 'Settings')

@section('content')
    {{-- Firebase Configuration Section --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-[#021c47]">Firebase Configuration</h3>
                <p class="text-sm text-gray-500 mt-1">Customer Authentication Setup</p>
            </div>
            @if($firebaseConfig['credentials_exists'])
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">✓ Configured</span>
            @else
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">⚠ Not Configured</span>
            @endif
        </div>
        <div class="p-6">
            {{-- Info Banner --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mb-6">
                <p class="text-sm text-blue-800">
                    <strong>📱 Customer Authentication Setup</strong><br>
                    Configure Firebase to enable OTP-based authentication for customers. You'll need a Firebase service account JSON file.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.firebase-config.update') }}" id="firebaseConfigForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Firebase Project ID --}}
                    <div>
                        <label for="firebase_project_id" class="block text-sm font-medium text-[#021c47] mb-2">
                            Firebase Project ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="firebase_project_id" id="firebase_project_id"
                               value="{{ $firebaseConfig['project_id'] }}" required
                               placeholder="your-firebase-project-id"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Find this in Firebase Console → Project Settings → General</p>
                    </div>

                    {{-- Firebase Database URL --}}
                    <div>
                        <label for="firebase_database_url" class="block text-sm font-medium text-[#021c47] mb-2">
                            Firebase Database URL
                        </label>
                        <input type="url" name="firebase_database_url" id="firebase_database_url"
                               value="{{ $firebaseConfig['database_url'] }}"
                               placeholder="https://your-project-id.firebaseio.com"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Optional: Only needed if using Firebase Realtime Database</p>
                    </div>

                    {{-- Firebase Storage Bucket --}}
                    <div>
                        <label for="firebase_storage_bucket" class="block text-sm font-medium text-[#021c47] mb-2">
                            Firebase Storage Bucket
                        </label>
                        <input type="text" name="firebase_storage_bucket" id="firebase_storage_bucket"
                               value="{{ $firebaseConfig['storage_bucket'] }}"
                               placeholder="your-project-id.appspot.com"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Optional: Only needed if using Firebase Storage</p>
                    </div>

                    {{-- Firebase Web API Key --}}
                    <div>
                        <label for="firebase_web_api_key" class="block text-sm font-medium text-[#021c47] mb-2">
                            Firebase Web API Key <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="firebase_web_api_key" id="firebase_web_api_key"
                               value="{{ $firebaseConfig['web_api_key'] }}" required
                               placeholder="AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Required for client-side Firebase authentication</p>
                    </div>

                    {{-- Firebase Auth Domain --}}
                    <div>
                        <label for="firebase_auth_domain" class="block text-sm font-medium text-[#021c47] mb-2">
                            Firebase Auth Domain <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="firebase_auth_domain" id="firebase_auth_domain"
                               value="{{ $firebaseConfig['auth_domain'] }}" required
                               placeholder="your-project-id.firebaseapp.com"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Usually your-project-id.firebaseapp.com</p>
                    </div>

                    {{-- Firebase Messaging Sender ID --}}
                    <div>
                        <label for="firebase_messaging_sender_id" class="block text-sm font-medium text-[#021c47] mb-2">
                            Firebase Messaging Sender ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="firebase_messaging_sender_id" id="firebase_messaging_sender_id"
                               value="{{ $firebaseConfig['messaging_sender_id'] }}" required
                               placeholder="123456789012"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Numeric sender ID for Firebase Cloud Messaging</p>
                    </div>

                    {{-- Firebase App ID --}}
                    <div>
                        <label for="firebase_app_id" class="block text-sm font-medium text-[#021c47] mb-2">
                            Firebase App ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="firebase_app_id" id="firebase_app_id"
                               value="{{ $firebaseConfig['app_id'] }}" required
                               placeholder="1:123456789012:web:abcdef123456"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Unique identifier for your Firebase web app</p>
                    </div>
                </div>

                {{-- Service Account JSON --}}
                <div class="mb-6">
                    <label for="firebase_credentials_json" class="block text-sm font-medium text-[#021c47] mb-2">
                        Service Account JSON <span class="text-red-500">*</span>
                    </label>
                    <textarea name="firebase_credentials_json" id="firebase_credentials_json" required rows="8"
                              placeholder='Paste your Firebase service account JSON here...'
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all font-mono text-sm"></textarea>
                    <p class="mt-1 text-xs text-gray-400">
                        <strong>How to get this:</strong> Firebase Console → Project Settings → Service Accounts → Generate New Private Key
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="testFirebaseConnection()" id="testButton"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                        Test Connection
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                        Save Configuration
                    </button>
                </div>
            </form>

            <div id="testResult" class="mt-6 hidden"></div>
        </div>
    </div>

    {{-- Social Media Links Section --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-[#021c47]">Social Media Links</h3>
                <p class="text-sm text-gray-500 mt-1">Manage footer social media links</p>
            </div>
            <button type="button" onclick="openAddModal()" class="px-4 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                + Add Link
            </button>
        </div>
        <div class="p-6">
            @if($socialMediaLinks->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#021c47] text-white">
                                <th class="px-4 py-3 text-left text-sm font-semibold w-16">Order</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Platform</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">URL</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold w-20">Icon</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold w-24">Status</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($socialMediaLinks as $link)
                                <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-[#021c47]">{{ $link->order }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-[#021c47] capitalize">{{ $link->platform }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ $link->url }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                            {{ Str::limit($link->url, 40) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($link->icon_file)
                                            <img src="{{ asset('storage/' . $link->icon_file) }}" alt="{{ $link->platform }}" class="w-8 h-8 object-contain mx-auto">
                                        @else
                                            <i class="{{ $link->icon }} text-xl text-gray-400"></i>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($link->is_enabled)
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Enabled</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">Disabled</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <button onclick="openEditModal({{ $link->id }}, '{{ $link->platform }}', '{{ $link->url }}', '{{ $link->icon }}', {{ $link->is_enabled ? 'true' : 'false' }}, {{ $link->order }}, '{{ $link->icon_file ? asset('storage/' . $link->icon_file) : '' }}')"
                                                    class="px-3 py-1.5 text-xs font-medium bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all">
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('admin.social-media.destroy', $link) }}" class="inline" onsubmit="return confirm('Delete this social media link?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-[#021c47] mb-2">No Social Media Links</h4>
                    <p class="text-gray-500 mb-4">Add your social media links to display them on the website footer.</p>
                    <button type="button" onclick="openAddModal()" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                        + Add Your First Link
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <div id="socialMediaModal" class="hidden fixed inset-0 bg-black/50 z-50 overflow-y-auto">
        <div class="max-w-lg mx-auto my-12 bg-white rounded-xl shadow-xl p-6">
            <h3 id="modalTitle" class="text-lg font-bold text-[#021c47] mb-6">Add Social Media Link</h3>

            <form id="socialMediaForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="space-y-5">
                    <div>
                        <label for="platform" class="block text-sm font-medium text-[#021c47] mb-2">Platform <span class="text-red-500">*</span></label>
                        <select name="platform" id="platform" required onchange="updateIcon()"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                            <option value="">Select Platform</option>
                            <option value="facebook">Facebook</option>
                            <option value="twitter">Twitter</option>
                            <option value="instagram">Instagram</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="youtube">YouTube</option>
                            <option value="tiktok">TikTok</option>
                            <option value="pinterest">Pinterest</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                    </div>

                    <div>
                        <label for="url" class="block text-sm font-medium text-[#021c47] mb-2">URL <span class="text-red-500">*</span></label>
                        <input type="url" name="url" id="url" required placeholder="https://facebook.com/yourpage"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    <div>
                        <label for="icon_file" class="block text-sm font-medium text-[#021c47] mb-2">Icon Image <span class="text-red-500">*</span></label>
                        <input type="file" name="icon_file" id="icon_file" accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Recommended: 64x64px (PNG, JPG, SVG, max 2MB)</p>
                        <div id="currentIcon" class="mt-2 hidden flex items-center gap-2">
                            <span class="text-xs text-gray-500">Current:</span>
                            <img id="currentIconPreview" src="" alt="Current icon" class="w-8 h-8 object-contain">
                        </div>
                    </div>

                    <div>
                        <label for="order" class="block text-sm font-medium text-[#021c47] mb-2">Display Order</label>
                        <input type="number" name="order" id="order" value="0" min="0"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Lower numbers appear first</p>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_enabled" id="is_enabled" value="1" checked class="w-4 h-4 text-[#93db4d] rounded focus:ring-[#93db4d]">
                            <span class="text-sm font-medium text-[#021c47]">Enable this link</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Toast Messages --}}
    @if(session('success'))
        <div id="successMessage" class="fixed top-5 right-5 bg-[#93db4d] text-[#021c47] px-6 py-4 rounded-lg shadow-lg font-medium z-50">
            {{ session('success') }}
        </div>
        <script>setTimeout(() => document.getElementById('successMessage')?.remove(), 3000);</script>
    @endif

    @if(session('error'))
        <div id="errorMessage" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg font-medium z-50">
            {{ session('error') }}
        </div>
        <script>setTimeout(() => document.getElementById('errorMessage')?.remove(), 5000);</script>
    @endif

    <script>
        const iconMap = {
            'facebook': 'fab fa-facebook-f',
            'twitter': 'fab fa-twitter',
            'instagram': 'fab fa-instagram',
            'linkedin': 'fab fa-linkedin-in',
            'youtube': 'fab fa-youtube',
            'tiktok': 'fab fa-tiktok',
            'pinterest': 'fab fa-pinterest',
            'whatsapp': 'fab fa-whatsapp'
        };

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Social Media Link';
            document.getElementById('socialMediaForm').action = '{{ route("admin.social-media.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('platform').value = '';
            document.getElementById('url').value = '';
            document.getElementById('icon_file').value = '';
            document.getElementById('order').value = '0';
            document.getElementById('is_enabled').checked = true;
            document.getElementById('currentIcon').classList.add('hidden');
            document.getElementById('socialMediaModal').classList.remove('hidden');
        }

        function openEditModal(id, platform, url, icon, isEnabled, order, iconFile) {
            document.getElementById('modalTitle').textContent = 'Edit Social Media Link';
            document.getElementById('socialMediaForm').action = `/admin/social-media/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('platform').value = platform;
            document.getElementById('url').value = url;
            document.getElementById('icon_file').value = '';
            document.getElementById('order').value = order;
            document.getElementById('is_enabled').checked = isEnabled;

            if (iconFile) {
                document.getElementById('currentIconPreview').src = iconFile;
                document.getElementById('currentIcon').classList.remove('hidden');
            } else {
                document.getElementById('currentIcon').classList.add('hidden');
            }

            document.getElementById('socialMediaModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('socialMediaModal').classList.add('hidden');
        }

        function updateIcon() {
            const platform = document.getElementById('platform').value;
            const iconInput = document.getElementById('icon');
            if (platform && iconMap[platform] && iconInput && !iconInput.value) {
                iconInput.value = iconMap[platform];
            }
        }

        document.getElementById('socialMediaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        function testFirebaseConnection() {
            const testButton = document.getElementById('testButton');
            const testResult = document.getElementById('testResult');

            testButton.disabled = true;
            testButton.textContent = 'Testing...';

            testResult.classList.remove('hidden');
            testResult.innerHTML = '<div class="text-center text-gray-500"><svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing Firebase connection...</div>';

            fetch('{{ route("admin.firebase-config.test") }}', {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                testResult.innerHTML = data.success
                    ? `<div class="bg-[#93db4d]/20 text-[#021c47] p-4 rounded-lg"><strong>✓ Success!</strong> ${data.message}</div>`
                    : `<div class="bg-red-100 text-red-700 p-4 rounded-lg"><strong>✗ Error:</strong> ${data.message}</div>`;
            })
            .catch(error => {
                testResult.innerHTML = `<div class="bg-red-100 text-red-700 p-4 rounded-lg"><strong>✗ Error:</strong> ${error.message}</div>`;
            })
            .finally(() => {
                testButton.disabled = false;
                testButton.textContent = 'Test Connection';
            });
        }
    </script>
@endsection
