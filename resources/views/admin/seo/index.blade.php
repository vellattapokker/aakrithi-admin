@extends('layouts.admin')

@section('title', 'SEO Configuration')

@section('styles')
<style>
    .seo-tabs { display: flex; gap: 0; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; }
    .seo-tab {
        padding: 0.75rem 1.25rem; cursor: pointer; font-size: 0.875rem; font-weight: 600;
        color: var(--color-text-light); border: none; background: none; border-bottom: 2px solid transparent;
        margin-bottom: -2px; transition: all 0.2s;
    }
    .seo-tab:hover { color: var(--color-text); }
    .seo-tab.active { color: var(--color-accent); border-bottom-color: var(--color-accent); }
    .seo-panel { display: none; }
    .seo-panel.active { display: block; }
    .seo-section-title {
        font-size: 1.125rem; font-weight: 700; color: var(--color-text);
        margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .seo-section-desc { font-size: 0.8rem; color: var(--color-text-light); margin-bottom: 1.5rem; line-height: 1.5; }
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px;
        border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    }
    .status-pill.active { background: rgba(76,175,80,0.1); color: #2e7d32; border: 1px solid rgba(76,175,80,0.3); }
    .status-pill.inactive { background: rgba(244,67,54,0.1); color: #c62828; border: 1px solid rgba(244,67,54,0.3); }
    .info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.75rem 0; border-bottom: 1px solid var(--color-border);
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 0.85rem; font-weight: 500; color: var(--color-text); }
    .info-value { font-size: 0.85rem; color: var(--color-text-light); }
    .grid-2-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media(max-width: 768px) { .grid-2-col { grid-template-columns: 1fr; } }
    .char-counter { font-size: 0.7rem; color: var(--color-text-light); text-align: right; margin-top: 4px; }
    .char-counter.warn { color: #e65100; }
    .char-counter.over { color: #c62828; }
    .preview-box {
        padding: 1rem; background: #FFFFFF; border: 1px solid #dfe1e5; border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); font-family: arial, sans-serif;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">SEO Command Center</h1>
    <p style="color: var(--color-text-light); font-size: 0.875rem;">Complete control over your store's search engine optimization, structured data, and tracking integrations.</p>
</div>

@if(session('success'))
<div style="padding: 1rem; background: rgba(76,175,80,0.1); border: 1px solid rgba(76,175,80,0.3); border-radius: 8px; margin-bottom: 1.5rem; color: #2e7d32; font-weight: 600; font-size: 0.875rem;">
    <i data-lucide="check-circle" style="width:16px; height:16px; display:inline; vertical-align: middle;"></i> {{ session('success') }}
</div>
@endif

{{-- Tab Navigation --}}
<div class="seo-tabs">
    <button class="seo-tab active" onclick="switchSeoTab(this, 'general')"><i data-lucide="file-text" style="width:14px;height:14px;"></i> General</button>
    <button class="seo-tab" onclick="switchSeoTab(this, 'homepage')"><i data-lucide="home" style="width:14px;height:14px;"></i> Homepage</button>
    <button class="seo-tab" onclick="switchSeoTab(this, 'social')"><i data-lucide="share-2" style="width:14px;height:14px;"></i> Social & OG</button>
    <button class="seo-tab" onclick="switchSeoTab(this, 'schema')"><i data-lucide="code" style="width:14px;height:14px;"></i> Schema / JSON-LD</button>
    <button class="seo-tab" onclick="switchSeoTab(this, 'technical')"><i data-lucide="settings" style="width:14px;height:14px;"></i> Technical</button>
    <button class="seo-tab" onclick="switchSeoTab(this, 'tracking')"><i data-lucide="bar-chart-3" style="width:14px;height:14px;"></i> Analytics</button>
    <button class="seo-tab" onclick="switchSeoTab(this, 'advanced')"><i data-lucide="terminal" style="width:14px;height:14px;"></i> Advanced</button>
</div>

<form action="{{ route('admin.seo.update') }}" method="POST">
    @csrf

    {{-- ===== TAB 1: GENERAL ===== --}}
    <div class="seo-panel active" id="panel-general">
        <div class="grid-2-col">
            <div class="card">
                <div class="seo-section-title"><i data-lucide="type" style="width:18px;"></i> Default Meta Tags</div>
                <div class="seo-section-desc">These are the fallback meta tags used when a page doesn't define its own.</div>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Default Site Title</label>
                        <input type="text" name="site_title" class="form-control" value="{{ $settings['site_title'] ?? 'Aakrithi - Premium Ethnic Wear' }}" maxlength="70">
                        <div class="char-counter"><span id="site_title_count">0</span> / 70</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Title Separator</label>
                        <select name="title_separator" class="form-control">
                            <option value=" | " {{ ($settings['title_separator'] ?? ' | ') == ' | ' ? 'selected' : '' }}> | (Pipe)</option>
                            <option value=" - " {{ ($settings['title_separator'] ?? '') == ' - ' ? 'selected' : '' }}> - (Dash)</option>
                            <option value=" › " {{ ($settings['title_separator'] ?? '') == ' › ' ? 'selected' : '' }}> › (Arrow)</option>
                            <option value=" — " {{ ($settings['title_separator'] ?? '') == ' — ' ? 'selected' : '' }}> — (Em Dash)</option>
                        </select>
                        <small style="color: var(--color-text-light); font-size: 0.75rem;">Used between page title and site name. e.g. "Product Name | Aakrithi"</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default Meta Description</label>
                        <textarea name="site_description" class="form-control" rows="4" maxlength="160">{{ $settings['site_description'] ?? '' }}</textarea>
                        <div class="char-counter"><span id="site_desc_count">0</span> / 160</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="fashion, ethnic wear, saree, kurta, Indian clothing">
                        <small style="color: var(--color-text-light); font-size: 0.75rem;">Comma-separated keywords. Used as fallback for all pages.</small>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="seo-section-title"><i data-lucide="eye" style="width:18px;"></i> Google Search Preview</div>
                <div class="seo-section-desc">How your homepage will appear in Google search results.</div>
                <div class="preview-box">
                    <div style="font-size: 14px; color: #202124; margin-bottom: 2px;">aakrithi.com</div>
                    <div style="color: #1a0dab; font-size: 20px; font-weight: 400; margin-bottom: 4px; line-height: 1.3;" id="gpreview-title">{{ $settings['site_title'] ?? 'Aakrithi - Premium Ethnic Wear' }}</div>
                    <div style="color: #4d5156; font-size: 14px; line-height: 1.58;" id="gpreview-desc">{{ $settings['site_description'] ?? 'Discover our curated collection of artisanal clothing designed for comfort and elegance.' }}</div>
                </div>

                <div style="margin-top: 2rem;">
                    <div class="seo-section-title"><i data-lucide="globe" style="width:18px;"></i> Canonical Domain</div>
                    <div class="seo-section-desc">Preferred domain for canonical URL tags.</div>
                    <div class="form-group">
                        <input type="url" name="canonical_url" class="form-control" value="{{ $settings['canonical_url'] ?? url('/') }}" placeholder="https://www.aakrithi.com">
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <div class="seo-section-title"><i data-lucide="languages" style="width:18px;"></i> Language & Locale</div>
                    <div class="seo-section-desc">Page language for search engines and social cards.</div>
                    <div class="form-group">
                        <select name="site_locale" class="form-control">
                            <option value="en_IN" {{ ($settings['site_locale'] ?? 'en_IN') == 'en_IN' ? 'selected' : '' }}>English (India)</option>
                            <option value="en_US" {{ ($settings['site_locale'] ?? '') == 'en_US' ? 'selected' : '' }}>English (US)</option>
                            <option value="hi_IN" {{ ($settings['site_locale'] ?? '') == 'hi_IN' ? 'selected' : '' }}>Hindi (India)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 2: HOMEPAGE ===== --}}
    <div class="seo-panel" id="panel-homepage">
        <div class="grid-2-col">
            <div class="card">
                <div class="seo-section-title"><i data-lucide="home" style="width:18px;"></i> Homepage SEO</div>
                <div class="seo-section-desc">Override meta tags specifically for the homepage. Leave blank to use defaults.</div>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Homepage Title</label>
                        <input type="text" name="homepage_title" class="form-control" value="{{ $settings['homepage_title'] ?? '' }}" placeholder="Aakrithi — Premium Handcrafted Ethnic Wear | Shop Online" maxlength="70">
                        <div class="char-counter"><span id="hp_title_count">0</span> / 70</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Homepage Meta Description</label>
                        <textarea name="homepage_description" class="form-control" rows="4" maxlength="160">{{ $settings['homepage_description'] ?? '' }}</textarea>
                        <div class="char-counter"><span id="hp_desc_count">0</span> / 160</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Homepage Keywords</label>
                        <input type="text" name="homepage_keywords" class="form-control" value="{{ $settings['homepage_keywords'] ?? '' }}" placeholder="ethnic wear online, buy saree, handcrafted kurta">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="seo-section-title"><i data-lucide="layout-grid" style="width:18px;"></i> Category SEO Defaults</div>
                <div class="seo-section-desc">Auto-generated SEO patterns for category pages. Use {category} as a placeholder.</div>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Category Title Pattern</label>
                        <input type="text" name="category_title_pattern" class="form-control" value="{{ $settings['category_title_pattern'] ?? '{category} Collection | Aakrithi' }}">
                        <small style="color: var(--color-text-light); font-size: 0.75rem;">e.g. "{category} Collection | Aakrithi" → "Apparels Collection | Aakrithi"</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category Description Pattern</label>
                        <textarea name="category_desc_pattern" class="form-control" rows="3">{{ $settings['category_desc_pattern'] ?? 'Browse our exclusive {category} collection. Shop premium quality handcrafted {category} at Aakrithi.' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product Title Pattern</label>
                        <input type="text" name="product_title_pattern" class="form-control" value="{{ $settings['product_title_pattern'] ?? '{product} | Aakrithi' }}">
                        <small style="color: var(--color-text-light); font-size: 0.75rem;">Fallback when a product doesn't have a custom meta title. e.g. "{product} | Aakrithi"</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 3: SOCIAL & OG ===== --}}
    <div class="seo-panel" id="panel-social">
        <div class="grid-2-col">
            <div class="card">
                <div class="seo-section-title"><i data-lucide="image" style="width:18px;"></i> Open Graph (Facebook / WhatsApp)</div>
                <div class="seo-section-desc">Controls how your site appears when shared on Facebook, WhatsApp, LinkedIn, etc.</div>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">OG Site Name</label>
                        <input type="text" name="og_site_name" class="form-control" value="{{ $settings['og_site_name'] ?? 'Aakrithi' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default OG Title</label>
                        <input type="text" name="og_title" class="form-control" value="{{ $settings['og_title'] ?? '' }}" placeholder="Aakrithi — Premium Ethnic Wear">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default OG Description</label>
                        <textarea name="og_description" class="form-control" rows="3">{{ $settings['og_description'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default OG Image (1200×630px recommended)</label>
                        <input type="url" name="og_image" class="form-control" value="{{ $settings['og_image'] ?? '' }}" placeholder="https://...">
                        <small style="color: var(--color-text-light); font-size: 0.75rem;">Used when a page or product doesn't have a specific OG image.</small>
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="twitter" style="width:18px;"></i> Twitter Cards</div>
                    <div class="seo-section-desc">Controls how your pages appear on Twitter / X.</div>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Twitter Card Type</label>
                            <select name="twitter_card_type" class="form-control">
                                <option value="summary_large_image" {{ ($settings['twitter_card_type'] ?? 'summary_large_image') == 'summary_large_image' ? 'selected' : '' }}>Summary with Large Image</option>
                                <option value="summary" {{ ($settings['twitter_card_type'] ?? '') == 'summary' ? 'selected' : '' }}>Summary</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Twitter Handle</label>
                            <input type="text" name="twitter_handle" class="form-control" value="{{ $settings['twitter_handle'] ?? '' }}" placeholder="@aakrithiclothing">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="link" style="width:18px;"></i> Social Media Profiles</div>
                    <div class="seo-section-desc">Used in the Organization JSON-LD schema for Google Knowledge Panel.</div>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Twitter / X URL</label>
                            <input type="url" name="twitter_url" class="form-control" value="{{ $settings['twitter_url'] ?? '' }}" placeholder="https://x.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? '' }}" placeholder="https://youtube.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pinterest URL</label>
                            <input type="url" name="pinterest_url" class="form-control" value="{{ $settings['pinterest_url'] ?? '' }}" placeholder="https://pinterest.com/...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 4: SCHEMA / JSON-LD ===== --}}
    <div class="seo-panel" id="panel-schema">
        <div class="grid-2-col">
            <div class="card">
                <div class="seo-section-title"><i data-lucide="building-2" style="width:18px;"></i> Organization Schema</div>
                <div class="seo-section-desc">Powers your Google Knowledge Panel and structured search results. All fields feed into Organization JSON-LD.</div>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="schema_business_name" class="form-control" value="{{ $settings['schema_business_name'] ?? 'Aakrithi' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Business Type</label>
                        <select name="schema_business_type" class="form-control">
                            <option value="Organization" {{ ($settings['schema_business_type'] ?? 'Organization') == 'Organization' ? 'selected' : '' }}>Organization</option>
                            <option value="LocalBusiness" {{ ($settings['schema_business_type'] ?? '') == 'LocalBusiness' ? 'selected' : '' }}>Local Business</option>
                            <option value="Store" {{ ($settings['schema_business_type'] ?? '') == 'Store' ? 'selected' : '' }}>Store</option>
                            <option value="ClothingStore" {{ ($settings['schema_business_type'] ?? '') == 'ClothingStore' ? 'selected' : '' }}>Clothing Store</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Logo URL</label>
                        <input type="url" name="schema_logo" class="form-control" value="{{ $settings['schema_logo'] ?? '' }}" placeholder="https://aakrithi.com/images/logo.png">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="schema_email" class="form-control" value="{{ $settings['schema_email'] ?? '' }}" placeholder="contact@aakrithi.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="schema_phone" class="form-control" value="{{ $settings['schema_phone'] ?? '' }}" placeholder="+91-XXXXXXXXXX">
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="map-pin" style="width:18px;"></i> Business Address</div>
                    <div class="seo-section-desc">Physical address for LocalBusiness schema and Google Maps integration.</div>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="schema_street" class="form-control" value="{{ $settings['schema_street'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="schema_city" class="form-control" value="{{ $settings['schema_city'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">State / Region</label>
                            <input type="text" name="schema_state" class="form-control" value="{{ $settings['schema_state'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="schema_postal" class="form-control" value="{{ $settings['schema_postal'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <input type="text" name="schema_country" class="form-control" value="{{ $settings['schema_country'] ?? 'IN' }}" placeholder="IN">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="clock" style="width:18px;"></i> Business Hours</div>
                    <div class="seo-section-desc">Opening hours for LocalBusiness schema.</div>
                    <div class="form-group">
                        <label class="form-label">Hours (e.g. Mon-Sat 10:00-20:00)</label>
                        <input type="text" name="schema_hours" class="form-control" value="{{ $settings['schema_hours'] ?? '' }}" placeholder="Mo-Sa 10:00-20:00">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 5: TECHNICAL SEO ===== --}}
    <div class="seo-panel" id="panel-technical">
        <div class="grid-2-col">
            <div class="card">
                <div class="seo-section-title"><i data-lucide="bot" style="width:18px;"></i> Robots & Indexing</div>
                <div class="seo-section-desc">Control how search engines crawl and index your site.</div>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: rgba(76,175,80,0.05); border: 1px solid rgba(76,175,80,0.2); border-radius: 8px;">
                        <input type="checkbox" name="allow_indexing" id="allow_indexing" value="1" {{ ($settings['allow_indexing'] ?? '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px;">
                        <div>
                            <label for="allow_indexing" style="font-weight: 600; color: var(--color-text); cursor: pointer;">Allow Search Engine Indexing</label>
                            <p style="font-size: 0.75rem; color: var(--color-text-light);">Uncheck to add a global noindex tag (useful for staging sites).</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Additional Robots Directives</label>
                        <textarea name="robots_extra" class="form-control" rows="4" placeholder="Disallow: /private-page&#10;Disallow: /staging/">{{ $settings['robots_extra'] ?? '' }}</textarea>
                        <small style="color: var(--color-text-light); font-size: 0.75rem;">Custom directives appended to robots.txt</small>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <div class="seo-section-title" style="font-size: 1rem;"><i data-lucide="check-circle" style="width:16px;"></i> Current Status</div>
                    <div style="margin-top: 0.75rem;">
                        <div class="info-row">
                            <span class="info-label">Sitemap</span>
                            <span class="status-pill active">● Active</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Sitemap URL</span>
                            <span class="info-value" style="font-family: monospace; font-size: 0.8rem;">/sitemap.xml</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Robots.txt</span>
                            <span class="status-pill active">● Configured</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Admin Blocked</span>
                            <span class="status-pill active">● Protected</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Lazy Loading</span>
                            <span class="status-pill active">● Enabled</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Canonical Tags</span>
                            <span class="status-pill active">● Active</span>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="image" style="width:18px;"></i> Favicon & Branding</div>
                    <div class="seo-section-desc">Favicon and theme color used in browser tabs and mobile.</div>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Favicon URL</label>
                            <input type="url" name="favicon_url" class="form-control" value="{{ $settings['favicon_url'] ?? '' }}" placeholder="https://aakrithi.com/favicon.ico">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Theme Color (for mobile browser)</label>
                            <input type="text" name="theme_color" class="form-control" value="{{ $settings['theme_color'] ?? '#C5A059' }}" placeholder="#C5A059">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="zap" style="width:18px;"></i> Performance</div>
                    <div class="seo-section-desc">Performance flags that affect Core Web Vitals scores.</div>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="checkbox" name="enable_preconnect" id="enable_preconnect" value="1" {{ ($settings['enable_preconnect'] ?? '1') == '1' ? 'checked' : '' }} style="width: 16px; height: 16px;">
                            <label for="enable_preconnect" style="font-size: 0.85rem; color: var(--color-text); cursor: pointer;">Enable DNS Preconnect hints</label>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="checkbox" name="enable_lazy_load" id="enable_lazy_load" value="1" {{ ($settings['enable_lazy_load'] ?? '1') == '1' ? 'checked' : '' }} style="width: 16px; height: 16px;">
                            <label for="enable_lazy_load" style="font-size: 0.85rem; color: var(--color-text); cursor: pointer;">Lazy load images globally</label>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="checkbox" name="enable_preload_fonts" id="enable_preload_fonts" value="1" {{ ($settings['enable_preload_fonts'] ?? '0') == '1' ? 'checked' : '' }} style="width: 16px; height: 16px;">
                            <label for="enable_preload_fonts" style="font-size: 0.85rem; color: var(--color-text); cursor: pointer;">Preload critical web fonts</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 6: ANALYTICS ===== --}}
    <div class="seo-panel" id="panel-tracking">
        <div class="grid-2-col">
            <div class="card">
                <div class="seo-section-title"><i data-lucide="bar-chart-3" style="width:18px;"></i> Google Analytics</div>
                <div class="seo-section-desc">Automatically injects the Google Analytics tracking script into your storefront.</div>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">GA4 Measurement ID</label>
                        <input type="text" name="google_analytics_id" class="form-control" value="{{ $settings['google_analytics_id'] ?? '' }}" placeholder="G-XXXXXXXXXX">
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem;">
                        <input type="checkbox" name="ga_anonymize_ip" id="ga_anonymize_ip" value="1" {{ ($settings['ga_anonymize_ip'] ?? '0') == '1' ? 'checked' : '' }} style="width: 16px; height: 16px;">
                        <label for="ga_anonymize_ip" style="font-size: 0.85rem; color: var(--color-text); cursor: pointer;">Anonymize IP addresses (GDPR compliance)</label>
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="search" style="width:18px;"></i> Google Search Console</div>
                    <div class="seo-section-desc">Verify ownership and track search performance.</div>
                    <div class="form-group">
                        <label class="form-label">Site Verification Code</label>
                        <input type="text" name="google_site_verification" class="form-control" value="{{ $settings['google_site_verification'] ?? '' }}" placeholder="Paste verification meta content value">
                    </div>
                </div>
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="tag" style="width:18px;"></i> Google Tag Manager</div>
                    <div class="seo-section-desc">Container ID for Google Tag Manager integration.</div>
                    <div class="form-group">
                        <label class="form-label">GTM Container ID</label>
                        <input type="text" name="gtm_id" class="form-control" value="{{ $settings['gtm_id'] ?? '' }}" placeholder="GTM-XXXXXXX">
                    </div>
                </div>
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="facebook" style="width:18px;"></i> Facebook Pixel</div>
                    <div class="seo-section-desc">Track conversions from Facebook and Instagram ads.</div>
                    <div class="form-group">
                        <label class="form-label">Facebook Pixel ID</label>
                        <input type="text" name="facebook_pixel_id" class="form-control" value="{{ $settings['facebook_pixel_id'] ?? '' }}" placeholder="XXXXXXXXXXXXXXXX">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 7: ADVANCED ===== --}}
    <div class="seo-panel" id="panel-advanced">
        <div class="grid-2-col">
            <div class="card">
                <div class="seo-section-title"><i data-lucide="code" style="width:18px;"></i> Custom Head Scripts</div>
                <div class="seo-section-desc">Raw HTML/JS injected into the &lt;head&gt; of every storefront page. Use for third-party tracking pixels, custom schema, etc.</div>
                <div class="form-group">
                    <textarea name="custom_head_scripts" class="form-control" rows="8" style="font-family: monospace; font-size: 0.8rem;" placeholder="<!-- Your custom scripts here -->">{{ $settings['custom_head_scripts'] ?? '' }}</textarea>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="code" style="width:18px;"></i> Custom Body Scripts</div>
                    <div class="seo-section-desc">Injected just before &lt;/body&gt;. Useful for chat widgets, popups, etc.</div>
                    <div class="form-group">
                        <textarea name="custom_body_scripts" class="form-control" rows="6" style="font-family: monospace; font-size: 0.8rem;" placeholder="<!-- Your custom scripts here -->">{{ $settings['custom_body_scripts'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="card">
                    <div class="seo-section-title"><i data-lucide="shield" style="width:18px;"></i> Content Security</div>
                    <div class="seo-section-desc">Additional security headers for better SEO trust signals.</div>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="checkbox" name="force_https" id="force_https" value="1" {{ ($settings['force_https'] ?? '0') == '1' ? 'checked' : '' }} style="width: 16px; height: 16px;">
                            <label for="force_https" style="font-size: 0.85rem; color: var(--color-text); cursor: pointer;">Force HTTPS redirect</label>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="checkbox" name="trailing_slash" id="trailing_slash" value="1" {{ ($settings['trailing_slash'] ?? '0') == '1' ? 'checked' : '' }} style="width: 16px; height: 16px;">
                            <label for="trailing_slash" style="font-size: 0.85rem; color: var(--color-text); cursor: pointer;">Remove trailing slashes from URLs</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Button (sticky) --}}
    <div style="position: sticky; bottom: 1.5rem; z-index: 10; margin-top: 2rem;">
        <div class="card" style="background: rgba(197, 160, 89, 0.08); border-color: rgba(197, 160, 89, 0.3); display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 0.85rem; color: var(--color-text-light);"><i data-lucide="info" style="width:14px; height:14px; vertical-align: middle;"></i> Changes will take effect on the storefront immediately after saving.</span>
            <button type="submit" class="btn btn-primary" style="min-width: 200px; justify-content: center; font-size: 1rem;"><i data-lucide="save" style="width:16px; height:16px;"></i> Save All Settings</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
function switchSeoTab(btn, panel) {
    document.querySelectorAll('.seo-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.seo-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + panel).classList.add('active');
}

// Character counters
function setupCounter(inputId, countId, max) {
    const el = document.querySelector('[name="' + inputId + '"]');
    const counter = document.getElementById(countId);
    if (!el || !counter) return;
    const update = () => {
        const len = el.value.length;
        counter.textContent = len;
        counter.parentElement.className = 'char-counter' + (len > max ? ' over' : (len > max * 0.85 ? ' warn' : ''));
    };
    el.addEventListener('input', update);
    update();
}

// Live Google Search Preview
function setupPreview() {
    const titleInput = document.querySelector('[name="site_title"]');
    const descInput = document.querySelector('[name="site_description"]');
    const previewTitle = document.getElementById('gpreview-title');
    const previewDesc = document.getElementById('gpreview-desc');
    if (!titleInput || !previewTitle) return;
    titleInput.addEventListener('input', () => { previewTitle.textContent = titleInput.value || 'Your Site Title'; });
    descInput.addEventListener('input', () => { previewDesc.textContent = descInput.value || 'Your meta description will appear here.'; });
}

document.addEventListener('DOMContentLoaded', function() {
    setupCounter('site_title', 'site_title_count', 70);
    setupCounter('site_description', 'site_desc_count', 160);
    setupCounter('homepage_title', 'hp_title_count', 70);
    setupCounter('homepage_description', 'hp_desc_count', 160);
    setupPreview();
    lucide.createIcons();
});
</script>
@endsection
