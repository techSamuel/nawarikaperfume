@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page_title', 'Site Settings')

@section('content')
@php $tab = request('tab', 'general'); @endphp
<div class="card" style="background: var(--bg-card); padding: 24px; border-radius: 12px; border: 1px solid var(--border-color);">
    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 14px;">
        @php
            $navTabs = [
                'general' => ['icon' => '⚙️', 'label' => 'General'],
                'home' => ['icon' => '🏠', 'label' => 'Home Page'],
                'contact' => ['icon' => '📞', 'label' => 'Contact'],
                'checkout' => ['icon' => '🛒', 'label' => 'Checkout'],
                'tracking' => ['icon' => '📈', 'label' => 'Tracking & API'],
                'ai' => ['icon' => '🤖', 'label' => 'AI & Gemini'],
                'policies' => ['icon' => '📜', 'label' => 'Policy Pages'],
            ];
        @endphp
        @foreach($navTabs as $tabKey => $tabInfo)
            <a href="{{ route('admin.settings.index', ['tab' => $tabKey]) }}" style="padding: 8px 16px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: all 0.2s; {{ $tab == $tabKey ? 'background: var(--accent-gradient); color: white;' : 'background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-color);' }}">
                <span>{{ $tabInfo['icon'] }}</span>
                <span>{{ $tabInfo['label'] }}</span>
            </a>
        @endforeach
    </div>

    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="current_tab" value="{{ $tab }}">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
            
            @if($tab == 'general')
            {{-- GENERAL SETTINGS --}}
            <div>
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">General Settings</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="site_name" style="display:block; margin-bottom: 8px;">Site Name</label>
                    <input type="text" name="site_name" id="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'LUXE' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="product_id_prefix" style="display:block; margin-bottom: 8px;">Product ID Prefix</label>
                    <input type="text" name="product_id_prefix" id="product_id_prefix" class="form-control" value="{{ $settings['product_id_prefix'] ?? 'NAWARIKA' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;" placeholder="e.g. NAWARIKA">
                    <small style="color: var(--text-muted); display:block; margin-top: 4px;">Prefix used for product IDs on the product specification page.</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="site_logo_dark" style="display:block; margin-bottom: 8px;">Site Logo (Dark Theme)</label>
                    @if(isset($settings['site_logo_dark']) && $settings['site_logo_dark'])
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('uploads/' . $settings['site_logo_dark']) }}" alt="Logo Dark" style="height: 50px; background: #fff; padding: 5px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="site_logo_dark" id="site_logo_dark" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="site_logo_light" style="display:block; margin-bottom: 8px;">Site Logo (White/Light Theme)</label>
                    @if(isset($settings['site_logo_light']) && $settings['site_logo_light'])
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('uploads/' . $settings['site_logo_light']) }}" alt="Logo Light" style="height: 50px; background: #1a1a1a; padding: 5px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="site_logo_light" id="site_logo_light" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="web_icon" style="display:block; margin-bottom: 8px;">Web Icon (Favicon)</label>
                    @if(isset($settings['web_icon']) && $settings['web_icon'])
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('uploads/' . $settings['web_icon']) }}" alt="Favicon" style="height: 32px; background: #fff; padding: 2px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="web_icon" id="web_icon" class="form-control" accept=".png,.ico,.jpg,.jpeg" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>
            </div>

            {{-- APPEARANCE --}}
            <div>
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Appearance</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="theme_mode" style="display:block; margin-bottom: 8px;">Default Theme Mode</label>
                    <select name="theme_mode" id="theme_mode" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        <option value="dark" {{ ($settings['theme_mode'] ?? 'dark') == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                        <option value="light" {{ ($settings['theme_mode'] ?? 'dark') == 'light' ? 'selected' : '' }}>Light Mode</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="primary_color" style="display:block; margin-bottom: 8px;">Primary Accent Color</label>
                    <input type="color" name="primary_color" id="primary_color" value="{{ $settings['primary_color'] ?? '#7c3aed' }}" style="width: 100%; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="secondary_color" style="display:block; margin-bottom: 8px;">Secondary Accent Color</label>
                    <input type="color" name="secondary_color" id="secondary_color" value="{{ $settings['secondary_color'] ?? '#a855f7' }}" style="width: 100%; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                </div>

                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-top: 32px;">Meta Data (SEO)</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="meta_title" style="display:block; margin-bottom: 8px;">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ $settings['meta_title'] ?? '' }}" placeholder="SEO Meta Title" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="meta_description" style="display:block; margin-bottom: 8px;">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" class="form-control" rows="3" placeholder="SEO Meta Description" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">{{ $settings['meta_description'] ?? '' }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="meta_keywords" style="display:block; margin-bottom: 8px;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="keyword1, keyword2, keyword3" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="social_preview_image" style="display:block; margin-bottom: 8px;">Social Media Preview Image</label>
                    @if(isset($settings['social_preview_image']) && $settings['social_preview_image'])
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('uploads/' . $settings['social_preview_image']) }}" alt="Social Preview" style="height: 100px; border-radius: 4px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="social_preview_image" id="social_preview_image" class="form-control" accept=".png,.jpg,.jpeg,.webp" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    <small style="color: var(--text-muted); display:block; margin-top: 4px;">Recommended size: 1200x630 pixels. Used when sharing your site on Facebook, Twitter, WhatsApp, etc.</small>
                </div>
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-top: 32px;">Delivery Settings</h3>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="global_delivery_charge" style="display:block; margin-bottom: 8px;">Universal Delivery Charge (৳)</label>
                    <input type="number" step="0.01" name="global_delivery_charge" id="global_delivery_charge" class="form-control" value="{{ $settings['global_delivery_charge'] ?? '50' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    <small style="color: var(--text-muted); display:block; margin-top: 4px;">Default delivery charge applied if a product does not have an individual charge set.</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="free_delivery_threshold" style="display:block; margin-bottom: 8px;">Free Delivery Threshold Amount (৳)</label>
                    <input type="number" step="0.01" name="free_delivery_threshold" id="free_delivery_threshold" class="form-control" value="{{ $settings['free_delivery_threshold'] ?? '500' }}" placeholder="e.g. 1000" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    <small style="color: var(--text-muted); display:block; margin-top: 4px;">Leave blank for no free delivery. If order subtotal is greater than or equal to this amount, delivery is free.</small>
                </div>
            </div>
            @endif

            @if($tab == 'home')
            {{-- HOME PAGE SETTINGS --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Home Page — Slider Settings</h3>
                
                {{-- GLOBAL SLIDER SETTINGS --}}
                <div style="background: rgba(124,58,237,0.06); border: 1px solid rgba(124,58,237,0.2); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                    <h4 style="margin-bottom: 16px; color: var(--accent-primary); font-size: 1rem;">⚙️ Slider Behavior</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Slider Height</label>
                            <input type="text" name="slider_height" class="form-control" value="{{ $settings['slider_height'] ?? '500px' }}" placeholder="e.g. 500px or 800px" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Auto-adjusts for mobile.</small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Autoplay Speed (ms)</label>
                            <input type="number" name="slider_autoplay_speed" class="form-control" value="{{ $settings['slider_autoplay_speed'] ?? '5000' }}" placeholder="5000" min="1000" max="20000" step="500" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Time between slides (1000 = 1s).</small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Transition Effect</label>
                            <select name="slider_effect" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="fade" {{ ($settings['slider_effect'] ?? 'fade') == 'fade' ? 'selected' : '' }}>Fade (Cinematic)</option>
                                <option value="slide" {{ ($settings['slider_effect'] ?? 'fade') == 'slide' ? 'selected' : '' }}>Slide (Classic)</option>
                                <option value="creative" {{ ($settings['slider_effect'] ?? 'fade') == 'creative' ? 'selected' : '' }}>Creative (Parallax)</option>
                                <option value="cube" {{ ($settings['slider_effect'] ?? 'fade') == 'cube' ? 'selected' : '' }}>Cube (3D)</option>
                                <option value="coverflow" {{ ($settings['slider_effect'] ?? 'fade') == 'coverflow' ? 'selected' : '' }}>Coverflow (3D Flow)</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-top: 16px;">
                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Overlay Style</label>
                            <select name="slider_overlay_style" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="gradient" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'gradient' ? 'selected' : '' }}>Gradient (Bottom-up)</option>
                                <option value="dark" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'dark' ? 'selected' : '' }}>Dark Tint</option>
                                <option value="glass" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'glass' ? 'selected' : '' }}>Glassmorphism</option>
                                <option value="none" {{ ($settings['slider_overlay_style'] ?? 'gradient') == 'none' ? 'selected' : '' }}>None (Image Only)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Overlay Opacity</label>
                            <input type="range" name="slider_overlay_opacity" min="0" max="100" value="{{ $settings['slider_overlay_opacity'] ?? '50' }}" oninput="document.getElementById('opacityVal').textContent = this.value + '%'" style="width: 100%; margin-top: 6px;">
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Current: <span id="opacityVal">{{ $settings['slider_overlay_opacity'] ?? '50' }}%</span></small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Navigation Style</label>
                            <select name="slider_nav_style" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="both" {{ ($settings['slider_nav_style'] ?? 'both') == 'both' ? 'selected' : '' }}>Arrows + Progress Bar</option>
                                <option value="arrows" {{ ($settings['slider_nav_style'] ?? 'both') == 'arrows' ? 'selected' : '' }}>Arrows Only</option>
                                <option value="dots" {{ ($settings['slider_nav_style'] ?? 'both') == 'dots' ? 'selected' : '' }}>Dots Only</option>
                                <option value="progress" {{ ($settings['slider_nav_style'] ?? 'both') == 'progress' ? 'selected' : '' }}>Progress Bar Only</option>
                                <option value="none" {{ ($settings['slider_nav_style'] ?? 'both') == 'none' ? 'selected' : '' }}>None (Auto Only)</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px; color: var(--info);">✨ Futuristic Text Appearance</label>
                            <select name="slider_text_style" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="modern" {{ ($settings['slider_text_style'] ?? 'modern') == 'modern' ? 'selected' : '' }}>Modern Clean (Default)</option>
                                <option value="futuristic_glass" {{ ($settings['slider_text_style'] ?? 'modern') == 'futuristic_glass' ? 'selected' : '' }}>Futuristic Glassmorphism (Tech Panel)</option>
                                <option value="cyber_neon" {{ ($settings['slider_text_style'] ?? 'modern') == 'cyber_neon' ? 'selected' : '' }}>Cyber Neon (Glowing Tech)</option>
                                <option value="holographic" {{ ($settings['slider_text_style'] ?? 'modern') == 'holographic' ? 'selected' : '' }}>Holographic Minimal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px; color: var(--info);">🚀 Text Animation</label>
                            <select name="slider_text_animation" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="fade_up" {{ ($settings['slider_text_animation'] ?? 'fade_up') == 'fade_up' ? 'selected' : '' }}>Fade Up (Smooth)</option>
                                <option value="tech_reveal" {{ ($settings['slider_text_animation'] ?? 'fade_up') == 'tech_reveal' ? 'selected' : '' }}>Tech Reveal (Data stream)</option>
                                <option value="glitch" {{ ($settings['slider_text_animation'] ?? 'fade_up') == 'glitch' ? 'selected' : '' }}>Glitch Effect</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Ken Burns Effect (Zoom)</label>
                            <select name="slider_kenburns" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <option value="1" {{ ($settings['slider_kenburns'] ?? '1') == '1' ? 'selected' : '' }}>Enabled — Slow zoom animation</option>
                                <option value="0" {{ ($settings['slider_kenburns'] ?? '1') == '0' ? 'selected' : '' }}>Disabled — Static images</option>
                            </select>
                            <small style="color: var(--text-muted); display:block; margin-top: 4px;">Cinematic slow zoom on each slide for a premium feel.</small>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom: 8px;">Announcement Bar Text</label>
                            <input type="text" name="announcement_text" class="form-control" value="{{ $settings['announcement_text'] ?? 'Bangladesh\'s Most Trusted E-Commerce Brand' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                {{-- PER-SLIDE SETTINGS --}}
                <div style="display: grid; grid-template-columns: 1fr; gap: 16px;">
                    @for($i = 1; $i <= 3; $i++)
                    <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); transition: border-color 0.3s ease;" 
                         onmouseover="this.style.borderColor='var(--accent-primary)'" onmouseout="this.style.borderColor='var(--border-color)'">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                            <span style="background: var(--accent-gradient); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; color: white;">{{ $i }}</span>
                            <h4 style="color: var(--text-primary); margin: 0;">Slide {{ $i }}</h4>
                            @if(isset($settings["slider_{$i}_image"]) && $settings["slider_{$i}_image"])
                                <span style="font-size: 0.75rem; padding: 2px 10px; background: rgba(16,185,129,0.15); color: #10b981; border-radius: 20px; font-weight: 600;">Active</span>
                            @else
                                <span style="font-size: 0.75rem; padding: 2px 10px; background: rgba(239,68,68,0.15); color: #ef4444; border-radius: 20px; font-weight: 600;">Empty</span>
                            @endif
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                            {{-- Image --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Image</label>
                                @if(isset($settings["slider_{$i}_image"]) && $settings["slider_{$i}_image"])
                                    <div style="margin-bottom: 10px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                                        <img src="{{ asset('uploads/' . $settings["slider_{$i}_image"]) }}" alt="Slide {{ $i }}" style="height: 80px; width: 100%; object-fit: cover; display: block;">
                                    </div>
                                @endif
                                <input type="file" name="slider_{{ $i }}_image" class="form-control" style="width: 100%; padding: 8px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px; font-size: 0.85rem;">
                            </div>

                            {{-- Link --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Link URL</label>
                                <input type="text" name="slider_{{ $i }}_link" class="form-control" value="{{ $settings["slider_{$i}_link"] ?? '' }}" placeholder="/shop or https://..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            </div>

                            {{-- Text Position --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Text Position</label>
                                <select name="slider_{{ $i }}_text_position" class="form-control" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                    <option value="center" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'center' ? 'selected' : '' }}>Center</option>
                                    <option value="left" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="right" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'right' ? 'selected' : '' }}>Right</option>
                                    <option value="bottom-left" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'bottom-left' ? 'selected' : '' }}>Bottom Left</option>
                                    <option value="bottom-center" {{ ($settings["slider_{$i}_text_position"] ?? 'center') == 'bottom-center' ? 'selected' : '' }}>Bottom Center</option>
                                </select>
                            </div>

                            {{-- Badge --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Badge Text</label>
                                <input type="text" name="slider_{{ $i }}_badge" class="form-control" value="{{ $settings["slider_{$i}_badge"] ?? '' }}" placeholder="e.g. 🔥 Hot Deal, ✨ New" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                                <small style="color: var(--text-muted); display:block; margin-top: 4px;">Small tag above headline.</small>
                            </div>

                            {{-- Headline --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Headline</label>
                                <input type="text" name="slider_{{ $i }}_headline" class="form-control" value="{{ $settings["slider_{$i}_headline"] ?? '' }}" placeholder="Main Headline" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            </div>

                            {{-- Button Text --}}
                            <div class="form-group">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Button Text</label>
                                <input type="text" name="slider_{{ $i }}_button_text" class="form-control" value="{{ $settings["slider_{$i}_button_text"] ?? '' }}" placeholder="Shop Now" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            </div>

                            {{-- Description --}}
                            <div class="form-group" style="grid-column: span 3;">
                                <label style="display:block; margin-bottom: 8px; font-weight: 600;">Description</label>
                                <textarea name="slider_{{ $i }}_description" class="form-control" rows="2" placeholder="Sub-headline or description..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">{{ $settings["slider_{$i}_description"] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif

            @if($tab == 'contact')
            {{-- CONTACT INFO --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Contact Info</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="contact_email" style="display:block; margin-bottom: 8px;">Contact Email</label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}" placeholder="support@luxestore.com" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label for="contact_phone" style="display:block; margin-bottom: 8px;">Contact Phone</label>
                        <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+880 1234 567890" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>
                </div>
            </div>
            @endif

            @if($tab == 'checkout')
            {{-- CHECKOUT SETTINGS --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Checkout Settings</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="cod_banner_title" style="display:block; margin-bottom: 8px;">COD Banner Title</label>
                    <input type="text" name="cod_banner_title" id="cod_banner_title" class="form-control" value="{{ $settings['cod_banner_title'] ?? 'Cash on Delivery' }}" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="cod_banner_text" style="display:block; margin-bottom: 8px;">COD Banner Text</label>
                    <textarea name="cod_banner_text" id="cod_banner_text" class="form-control" rows="2" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">{{ $settings['cod_banner_text'] ?? 'Pay when you receive your order. No online payment hassle — just shop, checkout, and pay at your doorstep.' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="checkout_warning_text" style="display:block; margin-bottom: 8px;">Checkout Warning Text</label>
                    <textarea name="checkout_warning_text" id="checkout_warning_text" class="form-control" placeholder="e.g. Please double check your phone number..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px; min-height: 80px;">{{ $settings['checkout_warning_text'] ?? '' }}</textarea>
                    <small style="color: var(--text-muted); display:block; margin-top: 4px;">This text will appear as a warning box at the top of the checkout form.</small>
                </div>
            </div>
            @endif

            @if($tab == 'tracking')
            {{-- TRACKING AND API --}}
            <div style="grid-column: span 2;">
                <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Tracking & Integrations</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="fb_pixel_id" style="display:block; margin-bottom: 8px;">Facebook Pixel ID</label>
                        <input type="text" name="fb_pixel_id" id="fb_pixel_id" class="form-control" value="{{ $settings['fb_pixel_id'] ?? '' }}" placeholder="e.g., 123456789012345" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>

                    <div class="form-group">
                        <label for="gtm_id" style="display:block; margin-bottom: 8px;">Google Tag Manager ID</label>
                        <input type="text" name="gtm_id" id="gtm_id" class="form-control" value="{{ $settings['gtm_id'] ?? '' }}" placeholder="e.g., GTM-XXXXXXX" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="capi_token" style="display:block; margin-bottom: 8px;">Facebook Conversions API (CAPI) Token</label>
                        <input type="text" name="capi_token" id="capi_token" class="form-control" value="{{ $settings['capi_token'] ?? '' }}" placeholder="Long CAPI Token string..." style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        <small style="color: var(--text-muted); display:block; margin-top: 4px;">Generated from Meta Events Manager &rarr; Settings &rarr; Conversions API &rarr; Generate access token.</small>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="capi_test_code" style="display:block; margin-bottom: 8px;">Meta CAPI Test Event Code (Optional)</label>
                        <input type="text" name="capi_test_code" id="capi_test_code" class="form-control" value="{{ $settings['capi_test_code'] ?? '' }}" placeholder="e.g., TEST12345" style="width: 100%; padding: 10px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                        <small style="color: var(--text-muted); display:block; margin-top: 4px;">Copy from Meta Events Manager &rarr; Test Events tab. Leave blank for live production events.</small>
                    </div>

                    {{-- CAPI TEST CONNECTION BUTTON & RESULT --}}
                    <div style="grid-column: span 2; padding: 16px; background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: 10px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <strong style="color: #93c5fd; font-size: 0.95rem;">⚡ Test Meta CAPI Real-time Connection</strong>
                                <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 0.82rem;">
                                    Send an instant diagnostic test event to Meta Graph API to verify your Pixel ID and Access Token.
                                </p>
                            </div>
                            <button type="button" id="btnTestCapi" onclick="testCapiConnection()" class="btn btn-outline" style="background: rgba(59, 130, 246, 0.15); border-color: rgba(59, 130, 246, 0.4); color: #60a5fa; font-weight: 600; padding: 8px 18px; border-radius: 6px; cursor: pointer;">
                                <span id="capiSpinner" style="display:none;">⏳ </span><span id="capiBtnText">Test CAPI Now</span>
                            </button>
                        </div>
                        <div id="capiResultBox" style="display:none; margin-top: 14px; padding: 12px 16px; border-radius: 8px; font-size: 0.85rem;"></div>
                    </div>

                    {{-- FACEBOOK PRODUCT CATALOG FEED BOX --}}
                    <div style="grid-column: span 2; padding: 16px; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 10px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <strong style="color: #6ee7b7; font-size: 0.95rem;">📦 Live Facebook Product Catalog Feed (XML)</strong>
                                <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 0.82rem;">
                                    Use this URL in Meta Commerce Manager &rarr; Catalog &rarr; Data Sources to sync your products automatically and fix catalog mismatch warnings.
                                </p>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ url('/facebook-catalog.xml') }}'); this.innerText='Copied!'; setTimeout(()=>this.innerText='Copy Feed URL', 2000)" class="btn btn-outline" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.4); color: #34d399; font-weight: 600; padding: 8px 18px; border-radius: 6px; cursor: pointer;">
                                Copy Feed URL
                            </button>
                        </div>
                        <div style="margin-top: 10px; background: rgba(0,0,0,0.3); padding: 8px 12px; border-radius: 6px; font-family: monospace; font-size: 0.85rem; color: #a7f3d0; word-break: break-all;">
                            {{ url('/facebook-catalog.xml') }}
                        </div>
                    </div>
                </div>

                <script>
                    function testCapiConnection() {
                        const pixelId = document.getElementById('fb_pixel_id').value.trim();
                        const token = document.getElementById('capi_token').value.trim();
                        const testCode = document.getElementById('capi_test_code').value.trim();
                        const resultBox = document.getElementById('capiResultBox');
                        const btn = document.getElementById('btnTestCapi');
                        const spinner = document.getElementById('capiSpinner');
                        const btnText = document.getElementById('capiBtnText');

                        if (!pixelId || !token) {
                            resultBox.style.display = 'block';
                            resultBox.style.background = 'rgba(239, 68, 68, 0.15)';
                            resultBox.style.border = '1px solid rgba(239, 68, 68, 0.3)';
                            resultBox.style.color = '#fca5a5';
                            resultBox.innerHTML = '❌ Please enter both Facebook Pixel ID and CAPI Token first.';
                            return;
                        }

                        btn.disabled = true;
                        btn.style.opacity = '0.7';
                        spinner.style.display = 'inline';
                        btnText.textContent = 'Contacting Meta...';
                        resultBox.style.display = 'none';

                        fetch('{{ route("admin.settings.test-capi") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                fb_pixel_id: pixelId,
                                capi_token: token,
                                capi_test_code: testCode
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            resultBox.style.display = 'block';
                            if (data.success) {
                                resultBox.style.background = 'rgba(16, 185, 129, 0.15)';
                                resultBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
                                resultBox.style.color = '#6ee7b7';
                                resultBox.innerHTML = '✅ <strong>CAPI is working perfectly!</strong><br><span style="font-size:0.82rem; color:#a7f3d0;">Meta received event successfully (Trace ID: ' + (data.fbtrace_id || 'N/A') + '). Check your Meta Events Manager Test Events tab!</span>';
                            } else {
                                resultBox.style.background = 'rgba(239, 68, 68, 0.15)';
                                resultBox.style.border = '1px solid rgba(239, 68, 68, 0.4)';
                                resultBox.style.color = '#fca5a5';
                                resultBox.innerHTML = '❌ <strong>CAPI Test Failed:</strong><br><span style="font-size:0.82rem;">' + (data.message || 'Check your Pixel ID and Access Token.') + '</span>';
                            }
                        })
                        .catch(err => {
                            resultBox.style.display = 'block';
                            resultBox.style.background = 'rgba(239, 68, 68, 0.15)';
                            resultBox.style.border = '1px solid rgba(239, 68, 68, 0.4)';
                            resultBox.style.color = '#fca5a5';
                            resultBox.innerHTML = '❌ Network error when testing CAPI connection.';
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                            spinner.style.display = 'none';
                            btnText.textContent = 'Test CAPI Now';
                        });
                    }
                </script>
            </div>
            @endif

            @if($tab == 'ai')
            {{-- AI & GEMINI SETTINGS --}}
            <div style="grid-column: span 2;">
                <div style="background: linear-gradient(135deg, rgba(124, 58, 237, 0.12) 0%, rgba(59, 130, 246, 0.08) 100%); border: 1px solid rgba(124, 58, 237, 0.3); border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #7c3aed 0%, #3b82f6 100%); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);">
                                🤖
                            </div>
                            <div>
                                <h3 style="margin: 0; color: #fff; font-size: 1.25rem; font-weight: 700;">Google Gemini AI Settings</h3>
                                <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 0.88rem;">
                                    Configure Google Gemini API for automated customer review generation on product edit pages.
                                </p>
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; padding: 4px 10px; background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); border-radius: 20px; font-weight: 600;">
                            Endpoint: v1beta/interactions
                        </span>
                    </div>

                    <div style="background: rgba(0, 0, 0, 0.25); border-radius: 8px; padding: 12px 16px; font-size: 0.82rem; color: #cbd5e1; border-left: 3px solid #7c3aed;">
                        💡 <strong>How it works:</strong> Product title, category, price, and description are passed to Google Gemini to synthesize realistic, authentic reviews with customizable ratings and languages (Bengali, Banglish, English).
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    {{-- API Key --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="gemini_api_key" style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-weight: 600;">
                            <span>Google Gemini API Key <span style="color: #ef4444;">*</span></span>
                            <a href="https://aistudio.google.com/app/apikey" target="_blank" style="font-size: 0.8rem; color: #a78bfa; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                🔑 Get Gemini API Key (Google AI Studio) ↗
                            </a>
                        </label>
                        <div style="position: relative;">
                            <input type="password" name="gemini_api_key" id="gemini_api_key" class="form-control" value="{{ $settings['gemini_api_key'] ?? '' }}" placeholder="AIzaSy..." style="width: 100%; padding: 12px 90px 12px 14px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px; font-family: monospace; font-size: 0.95rem;">
                            <button type="button" onclick="toggleApiKeyVisibility()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.08); border: 1px solid var(--border-color); color: #cbd5e1; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; cursor: pointer;">
                                <span id="toggleKeyText">Show</span>
                            </button>
                        </div>
                        <small style="color: var(--text-muted); display:block; margin-top: 6px;">Your API key is stored securely in your database settings.</small>
                    </div>

                    {{-- Model Selection --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="gemini_model" style="display:block; margin-bottom: 8px; font-weight: 600;">Gemini Model</label>
                        <input type="text" name="gemini_model" id="gemini_model" class="form-control" value="{{ $settings['gemini_model'] ?? 'gemini-2.5-flash' }}" placeholder="e.g. gemini-2.5-flash, gemini-3.8-flash" style="width: 100%; padding: 12px 14px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px; font-family: monospace; font-size: 0.95rem;">
                        <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Quick Select:</span>
                            <button type="button" class="btn-model-chip" onclick="selectModel('gemini-2.5-flash')" style="padding: 4px 10px; background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.4); color: #c4b5fd; border-radius: 14px; font-size: 0.75rem; cursor: pointer;">gemini-2.5-flash (Recommended)</button>
                            <button type="button" class="btn-model-chip" onclick="selectModel('gemini-3.8-flash')" style="padding: 4px 10px; background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.4); color: #93c5fd; border-radius: 14px; font-size: 0.75rem; cursor: pointer;">gemini-3.8-flash</button>
                            <button type="button" class="btn-model-chip" onclick="selectModel('gemini-2.0-flash')" style="padding: 4px 10px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: #cbd5e1; border-radius: 14px; font-size: 0.75rem; cursor: pointer;">gemini-2.0-flash</button>
                            <button type="button" class="btn-model-chip" onclick="selectModel('gemini-1.5-flash')" style="padding: 4px 10px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: #cbd5e1; border-radius: 14px; font-size: 0.75rem; cursor: pointer;">gemini-1.5-flash</button>
                            <button type="button" class="btn-model-chip" onclick="selectModel('gemini-1.5-pro')" style="padding: 4px 10px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: #cbd5e1; border-radius: 14px; font-size: 0.75rem; cursor: pointer;">gemini-1.5-pro</button>
                        </div>
                    </div>

                    {{-- Default Review Language --}}
                    <div class="form-group">
                        <label for="gemini_default_review_lang" style="display:block; margin-bottom: 8px; font-weight: 600;">Default Review Language</label>
                        <select name="gemini_default_review_lang" id="gemini_default_review_lang" class="form-control" style="width: 100%; padding: 12px 14px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            <option value="mixed" {{ ($settings['gemini_default_review_lang'] ?? 'mixed') == 'mixed' ? 'selected' : '' }}>Natural Mix (Bangla, English & Banglish)</option>
                            <option value="bangla" {{ ($settings['gemini_default_review_lang'] ?? 'mixed') == 'bangla' ? 'selected' : '' }}>Bengali only (বাংলা)</option>
                            <option value="banglish" {{ ($settings['gemini_default_review_lang'] ?? 'mixed') == 'banglish' ? 'selected' : '' }}>Banglish only (English script)</option>
                            <option value="english" {{ ($settings['gemini_default_review_lang'] ?? 'mixed') == 'english' ? 'selected' : '' }}>English only</option>
                        </select>
                        <small style="color: var(--text-muted); display:block; margin-top: 4px;">Default language used when generating product reviews.</small>
                    </div>

                    {{-- Default Rating Style --}}
                    <div class="form-group">
                        <label for="gemini_default_rating_style" style="display:block; margin-bottom: 8px; font-weight: 600;">Default Rating Style</label>
                        <select name="gemini_default_rating_style" id="gemini_default_rating_style" class="form-control" style="width: 100%; padding: 12px 14px; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: white; border-radius: 8px;">
                            <option value="mostly_5" {{ ($settings['gemini_default_rating_style'] ?? 'mostly_5') == 'mostly_5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ Mostly 5 Stars (Realistic 4-5 stars)</option>
                            <option value="all_5" {{ ($settings['gemini_default_rating_style'] ?? 'mostly_5') == 'all_5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars Only (Pristine)</option>
                            <option value="mixed" {{ ($settings['gemini_default_rating_style'] ?? 'mostly_5') == 'mixed' ? 'selected' : '' }}>⭐⭐⭐ to ⭐⭐⭐⭐⭐ Balanced (3 to 5 stars)</option>
                        </select>
                        <small style="color: var(--text-muted); display:block; margin-top: 4px;">Rating distribution when generating batches of reviews.</small>
                    </div>

                    {{-- Test Connection Box --}}
                    <div style="grid-column: span 2; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 20px; margin-top: 8px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h4 style="margin: 0 0 4px; color: var(--text-primary); font-size: 1rem;">⚡ Test Gemini API Connection</h4>
                                <p style="margin: 0; color: var(--text-muted); font-size: 0.85rem;">Send a test ping to Google Gemini to verify your API Key and Model before saving.</p>
                            </div>
                            <button type="button" id="btnTestGemini" onclick="testGeminiConnection()" style="padding: 10px 20px; background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: opacity 0.2s;">
                                <span id="testBtnSpinner" style="display: none;">⏳</span>
                                <span id="testBtnText">⚡ Test Connection</span>
                            </button>
                        </div>
                        <div id="testResultBox" style="display: none; margin-top: 14px; padding: 12px 16px; border-radius: 8px; font-size: 0.88rem;"></div>
                    </div>
                </div>

                <script>
                    function toggleApiKeyVisibility() {
                        const input = document.getElementById('gemini_api_key');
                        const btnText = document.getElementById('toggleKeyText');
                        if (input.type === 'password') {
                            input.type = 'text';
                            btnText.textContent = 'Hide';
                        } else {
                            input.type = 'password';
                            btnText.textContent = 'Show';
                        }
                    }

                    function selectModel(modelName) {
                        document.getElementById('gemini_model').value = modelName;
                    }

                    function testGeminiConnection() {
                        const apiKey = document.getElementById('gemini_api_key').value.trim();
                        const model = document.getElementById('gemini_model').value.trim();
                        const resultBox = document.getElementById('testResultBox');
                        const btn = document.getElementById('btnTestGemini');
                        const spinner = document.getElementById('testBtnSpinner');
                        const btnText = document.getElementById('testBtnText');

                        if (!apiKey) {
                            resultBox.style.display = 'block';
                            resultBox.style.background = 'rgba(239, 68, 68, 0.15)';
                            resultBox.style.border = '1px solid rgba(239, 68, 68, 0.3)';
                            resultBox.style.color = '#fca5a5';
                            resultBox.innerHTML = '❌ Please enter an API key to test.';
                            return;
                        }

                        btn.disabled = true;
                        btn.style.opacity = '0.7';
                        spinner.style.display = 'inline';
                        btnText.textContent = 'Testing...';
                        resultBox.style.display = 'none';

                        fetch('{{ route("admin.settings.test-gemini") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                gemini_api_key: apiKey,
                                gemini_model: model
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            resultBox.style.display = 'block';
                            if (data.success) {
                                resultBox.style.background = 'rgba(16, 185, 129, 0.15)';
                                resultBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
                                resultBox.style.color = '#6ee7b7';
                                resultBox.innerHTML = '✅ <strong>Connection Successful!</strong><br><span style="font-size:0.82rem; color:#a7f3d0;">' + data.message + '</span>';
                            } else {
                                resultBox.style.background = 'rgba(239, 68, 68, 0.15)';
                                resultBox.style.border = '1px solid rgba(239, 68, 68, 0.4)';
                                resultBox.style.color = '#fca5a5';
                                resultBox.innerHTML = '❌ <strong>Connection Failed:</strong><br><span style="font-size:0.82rem;">' + (data.message || 'Unknown error occurred.') + '</span>';
                            }
                        })
                        .catch(err => {
                            resultBox.style.display = 'block';
                            resultBox.style.background = 'rgba(239, 68, 68, 0.15)';
                            resultBox.style.border = '1px solid rgba(239, 68, 68, 0.4)';
                            resultBox.style.color = '#fca5a5';
                            resultBox.innerHTML = '❌ <strong>Request Error:</strong> ' + err.message;
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                            spinner.style.display = 'none';
                            btnText.textContent = '⚡ Test Connection';
                        });
                    }
                </script>
            </div>
            @endif

            @if($tab == 'policies')
            {{-- POLICY PAGES SETTINGS --}}
            <div style="grid-column: span 2;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h3 style="margin: 0; color: var(--text-primary); font-size: 1.3rem;">📜 Policy & Legal Pages Management</h3>
                        <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 0.9rem;">
                            Edit content for Return Policy, Shipping Policy, and Privacy Policy using HTML. Changes appear live on the front-end.
                        </p>
                    </div>
                </div>

                {{-- Policy Sub-Tabs --}}
                <div style="display: flex; gap: 10px; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; flex-wrap: wrap;">
                    <button type="button" class="policy-tab-btn active" id="btn_tab_return" onclick="switchPolicySubtab('return')" style="padding: 10px 20px; border-radius: 8px; border: 1px solid var(--accent-primary, #13b461); background: var(--accent-primary, #13b461); color: white; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <span>🔄</span> রিটার্ন পলিসি (Return Policy)
                    </button>
                    <button type="button" class="policy-tab-btn" id="btn_tab_shipping" onclick="switchPolicySubtab('shipping')" style="padding: 10px 20px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-tertiary); color: var(--text-secondary); font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <span>🚚</span> শিপিং পলিসি (Shipping Policy)
                    </button>
                    <button type="button" class="policy-tab-btn" id="btn_tab_privacy" onclick="switchPolicySubtab('privacy')" style="padding: 10px 20px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-tertiary); color: var(--text-secondary); font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <span>🔒</span> প্রাইভেসি পলিসি (Privacy Policy)
                    </button>
                </div>

                {{-- 1. RETURN POLICY PANEL --}}
                <div class="policy-editor-panel" id="panel_policy_return" style="display: block;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">🔄 Return Policy Content</span>
                            <span style="font-size: 0.8rem; padding: 2px 8px; background: rgba(19, 180, 97, 0.15); color: #10b981; border-radius: 12px; font-weight: 600;">Live Route: /return-policy</span>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('return.policy') }}" target="_blank" style="padding: 6px 14px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 6px; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                                🌐 View Live Page ↗
                            </a>
                        </div>
                    </div>

                    {{-- HTML Quick Toolbar --}}
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-bottom: none; border-radius: 8px 8px 0 0; padding: 8px 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size: 0.8rem; color: var(--text-muted); margin-right: 4px;">Insert HTML:</span>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<h3>', '</h3>', 'শিরোনাম লিখুন')" title="Heading 3">H3</button>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<h4>', '</h4>', 'উপ-শিরোনাম')" title="Heading 4">H4</button>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<p>', '</p>', 'প্যারাগ্রাফ লিখুন')" title="Paragraph">&lt;p&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<strong>', '</strong>', 'বোল্ড লেখা')" title="Bold">&lt;b&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<ul>\n  <li>', '</li>\n</ul>', 'তালিকা পয়েন্ট')" title="Bullet List">&lt;ul&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<div class=\"policy-highlight-box\" style=\"background: rgba(19, 180, 97, 0.08); border-left: 4px solid #13b461; padding: 16px; border-radius: 8px; margin-bottom: 20px;\">\n  <h4 style=\"margin-top:0; color:#13b461;\">বিজ্ঞপ্তি</h4>\n  <p>', '</p>\n</div>', 'জরুরি বার্তা')" title="Notice Box">💡 Notice Box</button>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<a href=\"#\" style=\"color: #13b461; text-decoration: underline;\">', '</a>', 'লিংক টেক্সট')" title="Link">🔗 Link</button>
                            <button type="button" class="btn-tool" onclick="insertTag('return_policy_content', '<br>', '', '')" title="Line Break">&lt;br&gt;</button>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="btn-view active" id="btn_view_code_return" onclick="toggleViewMode('return', 'code')">💻 HTML Code</button>
                            <button type="button" class="btn-view" id="btn_view_preview_return" onclick="toggleViewMode('return', 'preview')">👁️ Live Preview</button>
                        </div>
                    </div>

                    {{-- Textarea Editor --}}
                    <div id="editor_wrap_return">
                        <textarea name="return_policy_content" id="return_policy_content" rows="18" class="form-control html-code-editor" style="width: 100%; padding: 14px; background: #0f111a; border: 1px solid var(--border-color); color: #e2e8f0; border-radius: 0 0 8px 8px; font-family: 'Consolas', 'Fira Code', 'Courier New', monospace; font-size: 0.95rem; line-height: 1.6; min-height: 440px; resize: vertical;" placeholder="Enter return policy HTML content here...">{{ $settings['return_policy_content'] ?? '' }}</textarea>
                    </div>

                    {{-- Live Preview Box --}}
                    <div id="preview_wrap_return" style="display: none; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 0 0 8px 8px; padding: 32px; min-height: 440px; color: var(--text-secondary); line-height: 1.8;">
                    </div>
                </div>

                {{-- 2. SHIPPING POLICY PANEL --}}
                <div class="policy-editor-panel" id="panel_policy_shipping" style="display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">🚚 Shipping Policy Content</span>
                            <span style="font-size: 0.8rem; padding: 2px 8px; background: rgba(19, 180, 97, 0.15); color: #10b981; border-radius: 12px; font-weight: 600;">Live Route: /shipping-policy</span>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('shipping.policy') }}" target="_blank" style="padding: 6px 14px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 6px; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                                🌐 View Live Page ↗
                            </a>
                        </div>
                    </div>

                    {{-- HTML Quick Toolbar --}}
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-bottom: none; border-radius: 8px 8px 0 0; padding: 8px 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size: 0.8rem; color: var(--text-muted); margin-right: 4px;">Insert HTML:</span>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<h3>', '</h3>', 'শিরোনাম লিখুন')" title="Heading 3">H3</button>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<h4>', '</h4>', 'উপ-শিরোনাম')" title="Heading 4">H4</button>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<p>', '</p>', 'প্যারাগ্রাফ লিখুন')" title="Paragraph">&lt;p&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<strong>', '</strong>', 'বোল্ড লেখা')" title="Bold">&lt;b&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<ul>\n  <li>', '</li>\n</ul>', 'তালিকা পয়েন্ট')" title="Bullet List">&lt;ul&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<div class=\"policy-highlight-box\" style=\"background: rgba(19, 180, 97, 0.08); border-left: 4px solid #13b461; padding: 16px; border-radius: 8px; margin-bottom: 20px;\">\n  <h4 style=\"margin-top:0; color:#13b461;\">বিজ্ঞপ্তি</h4>\n  <p>', '</p>\n</div>', 'জরুরি বার্তা')" title="Notice Box">💡 Notice Box</button>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<a href=\"#\" style=\"color: #13b461; text-decoration: underline;\">', '</a>', 'লিংক টেক্সট')" title="Link">🔗 Link</button>
                            <button type="button" class="btn-tool" onclick="insertTag('shipping_policy_content', '<br>', '', '')" title="Line Break">&lt;br&gt;</button>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="btn-view active" id="btn_view_code_shipping" onclick="toggleViewMode('shipping', 'code')">💻 HTML Code</button>
                            <button type="button" class="btn-view" id="btn_view_preview_shipping" onclick="toggleViewMode('shipping', 'preview')">👁️ Live Preview</button>
                        </div>
                    </div>

                    {{-- Textarea Editor --}}
                    <div id="editor_wrap_shipping">
                        <textarea name="shipping_policy_content" id="shipping_policy_content" rows="18" class="form-control html-code-editor" style="width: 100%; padding: 14px; background: #0f111a; border: 1px solid var(--border-color); color: #e2e8f0; border-radius: 0 0 8px 8px; font-family: 'Consolas', 'Fira Code', 'Courier New', monospace; font-size: 0.95rem; line-height: 1.6; min-height: 440px; resize: vertical;" placeholder="Enter shipping policy HTML content here...">{{ $settings['shipping_policy_content'] ?? '' }}</textarea>
                    </div>

                    {{-- Live Preview Box --}}
                    <div id="preview_wrap_shipping" style="display: none; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 0 0 8px 8px; padding: 32px; min-height: 440px; color: var(--text-secondary); line-height: 1.8;">
                    </div>
                </div>

                {{-- 3. PRIVACY POLICY PANEL --}}
                <div class="policy-editor-panel" id="panel_policy_privacy" style="display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">🔒 Privacy Policy Content</span>
                            <span style="font-size: 0.8rem; padding: 2px 8px; background: rgba(19, 180, 97, 0.15); color: #10b981; border-radius: 12px; font-weight: 600;">Live Route: /privacy-policy</span>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('privacy.policy') }}" target="_blank" style="padding: 6px 14px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 6px; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                                🌐 View Live Page ↗
                            </a>
                        </div>
                    </div>

                    {{-- HTML Quick Toolbar --}}
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-bottom: none; border-radius: 8px 8px 0 0; padding: 8px 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size: 0.8rem; color: var(--text-muted); margin-right: 4px;">Insert HTML:</span>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<h3>', '</h3>', 'শিরোনাম লিখুন')" title="Heading 3">H3</button>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<h4>', '</h4>', 'উপ-শিরোনাম')" title="Heading 4">H4</button>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<p>', '</p>', 'প্যারাগ্রাফ লিখুন')" title="Paragraph">&lt;p&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<strong>', '</strong>', 'বোল্ড লেখা')" title="Bold">&lt;b&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<ul>\n  <li>', '</li>\n</ul>', 'তালিকা পয়েন্ট')" title="Bullet List">&lt;ul&gt;</button>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<div class=\"policy-highlight-box\" style=\"background: rgba(19, 180, 97, 0.08); border-left: 4px solid #13b461; padding: 16px; border-radius: 8px; margin-bottom: 20px;\">\n  <h4 style=\"margin-top:0; color:#13b461;\">বিজ্ঞপ্তি</h4>\n  <p>', '</p>\n</div>', 'জরুরি বার্তা')" title="Notice Box">💡 Notice Box</button>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<a href=\"#\" style=\"color: #13b461; text-decoration: underline;\">', '</a>', 'লিংক টেক্সট')" title="Link">🔗 Link</button>
                            <button type="button" class="btn-tool" onclick="insertTag('privacy_policy_content', '<br>', '', '')" title="Line Break">&lt;br&gt;</button>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="btn-view active" id="btn_view_code_privacy" onclick="toggleViewMode('privacy', 'code')">💻 HTML Code</button>
                            <button type="button" class="btn-view" id="btn_view_preview_privacy" onclick="toggleViewMode('privacy', 'preview')">👁️ Live Preview</button>
                        </div>
                    </div>

                    {{-- Textarea Editor --}}
                    <div id="editor_wrap_privacy">
                        <textarea name="privacy_policy_content" id="privacy_policy_content" rows="18" class="form-control html-code-editor" style="width: 100%; padding: 14px; background: #0f111a; border: 1px solid var(--border-color); color: #e2e8f0; border-radius: 0 0 8px 8px; font-family: 'Consolas', 'Fira Code', 'Courier New', monospace; font-size: 0.95rem; line-height: 1.6; min-height: 440px; resize: vertical;" placeholder="Enter privacy policy HTML content here...">{{ $settings['privacy_policy_content'] ?? '' }}</textarea>
                    </div>

                    {{-- Live Preview Box --}}
                    <div id="preview_wrap_privacy" style="display: none; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 0 0 8px 8px; padding: 32px; min-height: 440px; color: var(--text-secondary); line-height: 1.8;">
                    </div>
                </div>

                {{-- CSS for Policy Editor in Admin --}}
                <style>
                    .btn-tool {
                        background: rgba(255,255,255,0.06);
                        border: 1px solid var(--border-color);
                        color: var(--text-primary);
                        padding: 4px 10px;
                        border-radius: 4px;
                        font-size: 0.78rem;
                        font-family: inherit;
                        cursor: pointer;
                        transition: background 0.15s;
                    }
                    .btn-tool:hover {
                        background: rgba(255,255,255,0.15);
                        border-color: var(--accent-primary, #13b461);
                    }
                    .btn-view {
                        background: transparent;
                        border: 1px solid transparent;
                        color: var(--text-muted);
                        padding: 4px 12px;
                        border-radius: 4px;
                        font-size: 0.8rem;
                        font-weight: 600;
                        cursor: pointer;
                    }
                    .btn-view.active {
                        background: rgba(19, 180, 97, 0.2);
                        color: #10b981;
                        border-color: rgba(19, 180, 97, 0.4);
                    }
                    .html-code-editor:focus {
                        outline: none;
                        border-color: var(--accent-primary, #13b461) !important;
                        box-shadow: 0 0 0 2px rgba(19, 180, 97, 0.2);
                    }
                </style>

                {{-- JavaScript for Policy Editor --}}
                <script>
                    function switchPolicySubtab(type) {
                        ['return', 'shipping', 'privacy'].forEach(t => {
                            const btn = document.getElementById('btn_tab_' + t);
                            const panel = document.getElementById('panel_policy_' + t);
                            if (t === type) {
                                btn.style.background = 'var(--accent-primary, #13b461)';
                                btn.style.borderColor = 'var(--accent-primary, #13b461)';
                                btn.style.color = '#fff';
                                panel.style.display = 'block';
                            } else {
                                btn.style.background = 'var(--bg-tertiary)';
                                btn.style.borderColor = 'var(--border-color)';
                                btn.style.color = 'var(--text-secondary)';
                                panel.style.display = 'none';
                            }
                        });
                    }

                    function toggleViewMode(type, mode) {
                        const editorWrap = document.getElementById('editor_wrap_' + type);
                        const previewWrap = document.getElementById('preview_wrap_' + type);
                        const btnCode = document.getElementById('btn_view_code_' + type);
                        const btnPreview = document.getElementById('btn_view_preview_' + type);
                        const textarea = document.getElementById(type + '_policy_content');

                        if (mode === 'preview') {
                            previewWrap.innerHTML = textarea.value || '<p style="color:var(--text-muted); text-align:center;">কোনো কনটেন্ট লেখা হয়নি</p>';
                            editorWrap.style.display = 'none';
                            previewWrap.style.display = 'block';
                            btnPreview.classList.add('active');
                            btnCode.classList.remove('active');
                        } else {
                            editorWrap.style.display = 'block';
                            previewWrap.style.display = 'none';
                            btnCode.classList.add('active');
                            btnPreview.classList.remove('active');
                        }
                    }

                    function insertTag(textareaId, openTag, closeTag, defaultText) {
                        const textarea = document.getElementById(textareaId);
                        if (!textarea) return;

                        const start = textarea.selectionStart;
                        const end = textarea.selectionEnd;
                        const selectedText = textarea.value.substring(start, end) || defaultText;
                        const replacement = openTag + selectedText + closeTag;

                        textarea.focus();
                        textarea.setRangeText(replacement, start, end, 'end');
                    }
                </script>
            </div>
            @endif

        </div>

        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; text-align: right;">
            <button type="submit" class="btn" style="background: var(--accent-gradient); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
