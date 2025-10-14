@extends('layouts.admin')

@section('title', 'Settings - BixCash Admin')
@section('page-title', 'Settings')

@section('content')
    <!-- Social Media Links Section -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Social Media Links</h3>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                + Add Social Media Link
            </button>
        </div>
        <div class="card-body">
            @if($socialMediaLinks->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600; width: 50px;">Order</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Platform</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">URL</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Icon</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600; width: 100px;">Status</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600; width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($socialMediaLinks as $link)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $link->order }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <strong style="text-transform: capitalize;">{{ $link->platform }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <a href="{{ $link->url }}" target="_blank" style="color: #3498db; text-decoration: none;">
                                            {{ Str::limit($link->url, 50) }}
                                        </a>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($link->icon_file)
                                            <img src="{{ asset('storage/' . $link->icon_file) }}" alt="{{ $link->platform }}" style="width: 40px; height: 40px; object-fit: contain;">
                                        @else
                                            <i class="{{ $link->icon }}" style="font-size: 1.5rem; color: #666;"></i>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        @if($link->is_enabled)
                                            <span style="background: #27ae60; color: white; padding: 0.25rem 0.75rem; border-radius: 3px; font-size: 0.8rem;">
                                                Enabled
                                            </span>
                                        @else
                                            <span style="background: #95a5a6; color: white; padding: 0.25rem 0.75rem; border-radius: 3px; font-size: 0.8rem;">
                                                Disabled
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                            <button onclick="openEditModal({{ $link->id }}, '{{ $link->platform }}', '{{ $link->url }}', '{{ $link->icon }}', {{ $link->is_enabled ? 'true' : 'false' }}, {{ $link->order }}, '{{ $link->icon_file ? asset('storage/' . $link->icon_file) : '' }}')"
                                                    class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('admin.social-media.destroy', $link) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this social media link?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
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
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <i class="fas fa-share-alt fa-3x" style="color: #ddd; margin-bottom: 1rem;"></i>
                    <h4>No Social Media Links</h4>
                    <p>Add your social media links to display them on the website footer.</p>
                    <button type="button" class="btn btn-primary" onclick="openAddModal()" style="margin-top: 1rem;">
                        + Add Your First Social Media Link
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="socialMediaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; overflow-y: auto;">
        <div style="max-width: 600px; margin: 50px auto; background: white; border-radius: 8px; padding: 2rem; position: relative;">
            <h3 style="margin-bottom: 1.5rem;" id="modalTitle">Add Social Media Link</h3>

            <form id="socialMediaForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Platform *</label>
                    <select name="platform" id="platform" required onchange="updateIcon()" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
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

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">URL *</label>
                    <input type="url" name="url" id="url" required placeholder="https://facebook.com/yourpage" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Icon Image *</label>
                    <input type="file" name="icon_file" id="icon_file" accept="image/png,image/jpeg,image/jpg,image/svg+xml" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-top: 0.25rem;">
                        <strong>Recommended size: 64x64 pixels</strong> (PNG, JPG, or SVG format, max 2MB)
                    </small>
                    <div id="currentIcon" style="margin-top: 0.5rem; display: none;">
                        <small style="color: #666;">Current icon:</small>
                        <img id="currentIconPreview" src="" alt="Current icon" style="width: 40px; height: 40px; margin-left: 0.5rem; object-fit: contain;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Display Order</label>
                    <input type="number" name="order" id="order" value="0" min="0" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-top: 0.25rem;">Lower numbers appear first</small>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="is_enabled" id="is_enabled" value="1" checked style="margin-right: 0.5rem;">
                        <span style="font-weight: 500;">Enable this social media link</span>
                    </label>
                </div>

                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="position: fixed; top: 20px; right: 20px; background: #27ae60; color: white; padding: 1rem 1.5rem; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 9999;" id="successMessage">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('successMessage');
                if (msg) msg.remove();
            }, 3000);
        </script>
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
            document.getElementById('currentIcon').style.display = 'none';
            document.getElementById('socialMediaModal').style.display = 'block';
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

            // Show current icon if exists
            if (iconFile) {
                document.getElementById('currentIconPreview').src = iconFile;
                document.getElementById('currentIcon').style.display = 'block';
            } else {
                document.getElementById('currentIcon').style.display = 'none';
            }

            document.getElementById('socialMediaModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('socialMediaModal').style.display = 'none';
        }

        function updateIcon() {
            const platform = document.getElementById('platform').value;
            const iconInput = document.getElementById('icon');
            if (platform && iconMap[platform] && !iconInput.value) {
                iconInput.value = iconMap[platform];
            }
        }

        // Close modal when clicking outside
        document.getElementById('socialMediaModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection