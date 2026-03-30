@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Product</h1>
    <p style="color: var(--color-text-light); font-size: 0.875rem;">Create a new product entry in your catalog.</p>
</div>

<form action="{{ route('products.store') }}" method="POST" class="grid-2-1" id="productForm" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem; color: var(--color-text);">Basic Information</h3>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" id="product_name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Silk Banarasi Saree">
            </div>

            <div class="form-group">
                <label class="form-label">URL Slug</label>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="color: var(--color-text-light); font-size: 0.875rem;">/product/</span>
                    <input type="text" name="slug" id="product_slug" class="form-control" value="{{ old('slug') }}" required placeholder="silk-banarasi-saree">
                </div>
                <small style="color: var(--color-text-light); font-size: 0.75rem; margin-top: 4px; display: block;">Auto-generated from name. Must be unique.</small>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price') }}" required placeholder="2499">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="apparels">Apparels</option>
                        <option value="kutties">Kutties</option>
                        <option value="decors">Decors</option>
                        <option value="boutique">Boutique & Designs</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="6" required placeholder="Describe the craftsmanship and material...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem; color: var(--color-text);">Product Image</h3>
            
            {{-- Drag & Drop Upload Zone --}}
            <div id="dropZone" class="image-drop-zone" onclick="document.getElementById('image_file').click()">
                <input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;">
                <div id="dropPlaceholder" style="text-align: center;">
                    <i data-lucide="upload-cloud" style="width: 48px; height: 48px; color: var(--color-accent); margin-bottom: 0.75rem;"></i>
                    <p style="font-weight: 600; color: var(--color-text); margin-bottom: 0.25rem;">Drop image here or click to browse</p>
                    <p style="font-size: 0.75rem; color: var(--color-text-light);">JPEG, PNG, WebP — Max 5MB</p>
                </div>
                <img id="imagePreview" src="" alt="Preview" style="display: none; width: 100%; max-height: 250px; object-fit: contain; border-radius: 8px;">
            </div>
            <button type="button" id="removeImage" style="display: none; margin-top: 0.5rem; background: none; border: none; color: var(--color-error); font-size: 0.8rem; cursor: pointer; font-weight: 600;">✕ Remove Image</button>

            @error('image')
                <p style="color: var(--color-error); font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
            @error('image_file')
                <p style="color: var(--color-error); font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror

            {{-- URL Fallback --}}
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                <p style="font-size: 0.75rem; color: var(--color-text-light); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">Or paste an image URL</p>
                <input type="url" name="image" class="form-control" value="{{ old('image') }}" placeholder="https://...">
            </div>

            <div class="form-group mt-1">
                <label class="form-label">Badge (Optional)</label>
                <input type="text" name="badge" class="form-control" value="{{ old('badge') }}" placeholder="e.g. New Arrival">
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem; color: var(--color-text);">Search Engine Optimization</h3>
            
            <div class="form-group">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="Optimized title...">
                <div style="display: flex; justify-content: flex-end; margin-top: 4px;">
                    <span id="title_count" style="font-size: 0.75rem; color: var(--color-text-light);">0 / 60</span>
                </div>
            </div>
            
            <div class="form-group mt-1">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" id="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
                <div style="display: flex; justify-content: flex-end; margin-top: 4px;">
                    <span id="desc_count" style="font-size: 0.75rem; color: var(--color-text-light);">0 / 160</span>
                </div>
            </div>

            <div class="form-group mt-1">
                <label class="form-label">Open Graph Image URL (Optional)</label>
                <input type="url" name="og_image" class="form-control" value="{{ old('og_image') }}" placeholder="Specific image for social sharing">
            </div>

            <div class="form-group mt-1" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: rgba(244, 67, 54, 0.05); border: 1px solid rgba(244, 67, 54, 0.2); border-radius: 8px;">
                <input type="checkbox" name="is_noindex" id="is_noindex" value="1" style="width: 18px; height: 18px;">
                <div>
                    <label for="is_noindex" style="font-weight: 600; color: var(--color-error); cursor: pointer;">Hide from Search Engines</label>
                    <p style="font-size: 0.75rem; color: var(--color-text-light);">Check this to add a noindex tag. Google will not show this product.</p>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <h4 style="font-size: 0.875rem; color: var(--color-text-light); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Google Search Preview</h4>
                <div style="padding: 1rem; background: #FFFFFF; border: 1px solid #dfe1e5; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); font-family: arial, sans-serif;">
                    <div style="font-size: 14px; color: #202124; margin-bottom: 2px;">aakrithi.com <span style="color: #5f6368;">› product › <span id="preview-url">product-slug</span></span></div>
                    <div style="color: #1a0dab; font-size: 20px; text-decoration: none; font-weight: 400; margin-bottom: 4px; line-height: 1.3;" id="preview-title">Product Name | Aakrithi</div>
                    <div style="color: #4d5156; font-size: 14px; line-height: 1.58;" id="preview-desc">Provide a meta description to see how this product will look in Google Search results.</div>
                </div>
            </div>
        </div>

        <div class="card" style="background: rgba(197, 160, 89, 0.05); border-color: rgba(197, 160, 89, 0.2);">
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 1rem;">Publish Product</button>
            <a href="{{ route('products.index') }}" style="display: block; text-align: center; margin-top: 1rem; color: var(--color-text-light); font-size: 0.875rem;">Cancel</a>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('product_name');
    const slugInput = document.getElementById('product_slug');
    const metaTitleInput = document.getElementById('meta_title');
    const metaDescInput = document.getElementById('meta_description');
    
    const previewUrl = document.getElementById('preview-url');
    const previewTitle = document.getElementById('preview-title');
    const previewDesc = document.getElementById('preview-desc');
    
    const titleCount = document.getElementById('title_count');
    const descCount = document.getElementById('desc_count');

    // Auto-generate slug from name
    nameInput.addEventListener('keyup', function() {
        if (!slugInput.getAttribute('data-manually-edited')) {
            const slug = this.value.toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove non-word chars
                .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with dashes
                .replace(/^-+|-+$/g, ''); // Trim dashes
            slugInput.value = slug;
            previewUrl.textContent = slug || 'product-slug';
        }
        updatePreview();
    });

    slugInput.addEventListener('input', function() {
        this.setAttribute('data-manually-edited', 'true');
        previewUrl.textContent = this.value || 'product-slug';
    });

    function updatePreview() {
        // Title Preview
        let title = metaTitleInput.value.trim();
        if (!title) {
            title = nameInput.value.trim() ? nameInput.value.trim() + ' | Aakrithi' : 'Product Name | Aakrithi';
        }
        previewTitle.textContent = title;
        
        let tLen = metaTitleInput.value.length;
        titleCount.textContent = tLen + ' / 60';
        titleCount.style.color = tLen > 60 ? 'var(--color-error)' : 'var(--color-text-light)';

        // Description Preview
        let desc = metaDescInput.value.trim();
        previewDesc.textContent = desc ? desc : 'Provide a meta description to see how this product will look in Google Search results.';
        
        let dLen = desc.length;
        descCount.textContent = dLen + ' / 160';
        descCount.style.color = dLen > 160 ? 'var(--color-error)' : 'var(--color-text-light)';
    }

    metaTitleInput.addEventListener('input', updatePreview);
    metaDescInput.addEventListener('input', updatePreview);
    
    // Initial run
    updatePreview();

    // === IMAGE UPLOAD DRAG & DROP ===
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('image_file');
    const imagePreview = document.getElementById('imagePreview');
    const dropPlaceholder = document.getElementById('dropPlaceholder');
    const removeBtn = document.getElementById('removeImage');

    function showPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            dropPlaceholder.style.display = 'none';
            removeBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    }

    fileInput.addEventListener('change', function() {
        if (this.files[0]) showPreview(this.files[0]);
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', function() {
        this.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            showPreview(file);
        }
    });

    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.value = '';
        imagePreview.style.display = 'none';
        imagePreview.src = '';
        dropPlaceholder.style.display = 'block';
        this.style.display = 'none';
    });
});
</script>
@endsection
